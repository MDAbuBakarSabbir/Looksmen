<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\Admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FinanceController extends Controller
{
    /**
     * Display Finance Ledger Dashboard with live statistics and recent transactions.
     */
    public function index(Request $request)
    {
        $query = FinanceTransaction::query();

        // 1. Filter: Entry Type (INCOME / EXPENSE)
        if ($request->filled('type') && in_array($request->type, ['INCOME', 'EXPENSE'])) {
            $query->where('entry_type', $request->type);
        }

        // 2. Filter: Category
        if ($request->filled('category') && $request->category !== 'ALL') {
            $query->where('category', $request->category);
        }

        // 3. Filter: Staff Name
        if ($request->filled('staff') && $request->staff !== 'ALL') {
            $query->where('staff_name', $request->staff);
        }

        // 4. Filter: Search Query (notes, category, staff, payment_method, amount)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('staff_name', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        // 5. Filter: Timeframe
        if ($request->filled('timeframe')) {
            $timeframe = $request->timeframe;
            if ($timeframe === 'today') {
                $query->whereDate('transaction_date', Carbon::today());
            } elseif ($timeframe === 'week') {
                $query->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($timeframe === 'month') {
                $query->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($timeframe === 'year') {
                $query->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            } elseif ($timeframe === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('transaction_date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }
        }

        // Fetch recent transactions (limit to 30 for the dashboard card list)
        $transactions = $query->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->take(50)->get();

        // Summary KPI Metrics
        $totalIncome = (float) FinanceTransaction::where('entry_type', 'INCOME')->sum('amount');
        $totalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;
        $totalTransactions = FinanceTransaction::count();
        $incomeEntriesCount = FinanceTransaction::where('entry_type', 'INCOME')->count();
        $expenseEntriesCount = FinanceTransaction::where('entry_type', 'EXPENSE')->count();

        // Operational Breakdown & Category Distribution (Top 3 categories by expenditure / revenue)
        $categoryBreakdown = FinanceTransaction::select('category', 'entry_type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as entries_count'))
            ->groupBy('category', 'entry_type')
            ->orderByDesc('total_amount')
            ->take(3)
            ->get()
            ->map(function ($item) use ($totalExpense, $totalIncome) {
                $base = $item->entry_type === 'EXPENSE' ? ($totalExpense ?: 1) : ($totalIncome ?: 1);
                $item->percentage = min(100, round(($item->total_amount / $base) * 100));
                return $item;
            });

        // Staff list for suggestions and filters
        $staffList = Admins::pluck('name')
            ->merge(FinanceTransaction::distinct()->pluck('staff_name'))
            ->unique()
            ->filter()
            ->values();

        // Return JSON if AJAX requested (for dynamic table / metrics refresh)
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'transactions' => $transactions,
                'metrics' => [
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                    'netBalance' => $netBalance,
                    'totalTransactions' => $totalTransactions,
                    'incomeEntriesCount' => $incomeEntriesCount,
                    'expenseEntriesCount' => $expenseEntriesCount,
                ],
                'count' => $transactions->count(),
            ]);
        }

        return view('adminDash.finance.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'totalTransactions',
            'incomeEntriesCount',
            'expenseEntriesCount',
            'categoryBreakdown',
            'staffList'
        ));
    }

    /**
     * Store a newly created finance transaction in storage.
     * Converts and saves invoice / receipt images in .webp format.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_type' => 'required|in:INCOME,EXPENSE',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'payment_method' => 'required|string|max:100',
            'transaction_date' => 'required|date',
            'staff_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $imageName = null;
        if ($request->hasFile('receipt_image')) {
            $imageName = $this->saveImageAsWebp($request->file('receipt_image'));
        }

        $transaction = FinanceTransaction::create([
            'entry_type' => $validated['entry_type'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'payment_method' => $validated['payment_method'],
            'transaction_date' => Carbon::parse($validated['transaction_date']),
            'staff_name' => $validated['staff_name'],
            'notes' => $validated['notes'] ?? null,
            'receipt_image' => $imageName,
            'admin_id' => auth('admin')->id() ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ucfirst(strtolower($transaction->entry_type)) . ' entry of ৳' . number_format($transaction->amount, 2) . ' recorded successfully!',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Update an existing transaction record.
     */
    public function update(Request $request, $id)
    {
        $transaction = FinanceTransaction::findOrFail($id);

        $validated = $request->validate([
            'entry_type' => 'required|in:INCOME,EXPENSE',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'payment_method' => 'required|string|max:100',
            'transaction_date' => 'required|date',
            'staff_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $receiptImageName = $transaction->receipt_image;
        if ($request->hasFile('receipt_image')) {
            // Delete old file if exists
            if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/' . $transaction->receipt_image))) {
                @unlink(public_path('Uploads/finance/' . $transaction->receipt_image));
            }
            $receiptImageName = $this->saveImageAsWebp($request->file('receipt_image'));
        } elseif ($request->input('remove_receipt') == '1') {
            if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/' . $transaction->receipt_image))) {
                @unlink(public_path('Uploads/finance/' . $transaction->receipt_image));
            }
            $receiptImageName = null;
        }

        $transaction->update([
            'entry_type' => $validated['entry_type'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'payment_method' => $validated['payment_method'],
            'transaction_date' => Carbon::parse($validated['transaction_date']),
            'staff_name' => $validated['staff_name'],
            'notes' => $validated['notes'] ?? null,
            'receipt_image' => $receiptImageName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction #' . $transaction->id . ' updated successfully!',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Remove the specified transaction from database.
     */
    public function destroy($id)
    {
        $transaction = FinanceTransaction::findOrFail($id);

        // Remove receipt image from disk if exists
        if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/' . $transaction->receipt_image))) {
            @unlink(public_path('Uploads/finance/' . $transaction->receipt_image));
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction record deleted permanently.',
        ]);
    }

    /**
     * Export all ledger records as a downloadable CSV/Excel spreadsheet.
     */
    public function export(Request $request)
    {
        $query = FinanceTransaction::query();

        if ($request->filled('type') && in_array($request->type, ['INCOME', 'EXPENSE'])) {
            $query->where('entry_type', $request->type);
        }
        if ($request->filled('category') && $request->category !== 'ALL') {
            $query->where('category', $request->category);
        }
        if ($request->filled('staff') && $request->staff !== 'ALL') {
            $query->where('staff_name', $request->staff);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $filename = 'finance_ledger_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($file, [
                'Transaction ID',
                'Type',
                'Amount (BDT ৳)',
                'Category',
                'Payment Method',
                'Date & Time',
                'Recorded By',
                'Notes / Details',
                'Receipt Image File',
            ]);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    '#FT-' . str_pad($tx->id, 5, '0', STR_PAD_LEFT),
                    $tx->entry_type,
                    $tx->amount,
                    $tx->category,
                    $tx->payment_method,
                    $tx->transaction_date ? $tx->transaction_date->format('Y-m-d H:i:s') : '',
                    $tx->staff_name,
                    $tx->notes ?? '',
                    $tx->receipt_image ? asset('Uploads/finance/' . $tx->receipt_image) : 'None',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display full ledger transaction history page with pagination.
     */
    public function allTrans(Request $request)
    {
        $query = FinanceTransaction::query();

        if ($request->filled('type') && in_array($request->type, ['INCOME', 'EXPENSE'])) {
            $query->where('entry_type', $request->type);
        }
        if ($request->filled('category') && $request->category !== 'ALL') {
            $query->where('category', $request->category);
        }
        if ($request->filled('staff') && $request->staff !== 'ALL') {
            $query->where('staff_name', $request->staff);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('staff_name', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(25)->withQueryString();

        $totalIncome = (float) FinanceTransaction::where('entry_type', 'INCOME')->sum('amount');
        $totalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $staffList = Admins::pluck('name')
            ->merge(FinanceTransaction::distinct()->pluck('staff_name'))
            ->unique()
            ->filter()
            ->values();

        return view('adminDash.finance.allTrans', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'staffList'
        ));
    }

    /**
     * Helper to convert and save any uploaded receipt image strictly as .webp format.
     */
    protected function saveImageAsWebp($file): string
    {
        $dir = public_path('Uploads/finance');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'receipt_' . time() . '_' . Str::random(8) . '.webp';
        $fullPath = $dir . '/' . $fileName;

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($file);
            $image->scaleDown(width: 1400);
            $image->save($fullPath, quality: 80);
        } catch (\Throwable $e) {
            // High-reliability GD fallback if Intervention decode fails
            $srcImage = null;
            $mime = $file->getMimeType();

            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $srcImage = @imagecreatefromjpeg($file->getRealPath());
            } elseif ($mime === 'image/png') {
                $srcImage = @imagecreatefrompng($file->getRealPath());
            } elseif ($mime === 'image/webp') {
                $srcImage = @imagecreatefromwebp($file->getRealPath());
            }

            if ($srcImage && function_exists('imagewebp')) {
                imagepalettetotruecolor($srcImage);
                imagealphablending($srcImage, true);
                imagesavealpha($srcImage, true);
                imagewebp($srcImage, $fullPath, 80);
                imagedestroy($srcImage);
            } else {
                $file->move($dir, $fileName);
            }
        }

        return $fileName;
    }
}
