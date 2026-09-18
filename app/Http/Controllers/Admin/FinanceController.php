<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admins;
use App\Models\FinanceTransaction;
use App\Models\Investor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FinanceController extends Controller
{
    /**
     * Display Finance Ledger Dashboard with live statistics and recent transactions.
     */
    public function index(Request $request)
    {
        $timeframe = $request->get('timeframe', 'month');

        $baseQuery = FinanceTransaction::query();

        // 1. Filter: Timeframe
        if ($timeframe === 'today') {
            $baseQuery->whereDate('transaction_date', Carbon::today());
        } elseif ($timeframe === 'week') {
            $baseQuery->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($timeframe === 'month') {
            $baseQuery->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($timeframe === 'year') {
            $baseQuery->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } elseif ($timeframe === 'custom') {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $baseQuery->whereBetween('transaction_date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ]);
            } elseif ($request->filled('start_date')) {
                $baseQuery->where('transaction_date', '>=', Carbon::parse($request->start_date)->startOfDay());
            } elseif ($request->filled('end_date')) {
                $baseQuery->where('transaction_date', '<=', Carbon::parse($request->end_date)->endOfDay());
            }
        }
        // If $timeframe === 'all', no date constraint

        // 2. Filter: Category
        if ($request->filled('category') && $request->category !== 'ALL') {
            if ($request->category === 'ALL_INVESTMENTS') {
                $baseQuery->whereIn('category', ['Investment / Capital', 'Investment Withdrawal']);
            } else {
                $baseQuery->where('category', $request->category);
            }
        }

        // 3. Filter: Staff Name
        if ($request->filled('staff') && $request->staff !== 'ALL') {
            $baseQuery->where('staff_name', $request->staff);
        }

        // 4. Filter: Search Query (notes, category, staff, payment_method, amount)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $baseQuery->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('staff_name', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        // Summary KPI Metrics calculated on the active filters
        $type = $request->get('type', 'ALL');
        if ($type === 'INCOME') {
            $totalIncome = (float) (clone $baseQuery)->where('entry_type', 'INCOME')->sum('amount');
            $totalExpense = 0.0;
            $incomeEntriesCount = (clone $baseQuery)->where('entry_type', 'INCOME')->count();
            $expenseEntriesCount = 0;
            $netBalance = $totalIncome;
            $totalTransactions = $incomeEntriesCount;
        } elseif ($type === 'EXPENSE') {
            $totalIncome = 0.0;
            $totalExpense = (float) (clone $baseQuery)->where('entry_type', 'EXPENSE')->sum('amount');
            $incomeEntriesCount = 0;
            $expenseEntriesCount = (clone $baseQuery)->where('entry_type', 'EXPENSE')->count();
            $netBalance = -$totalExpense;
            $totalTransactions = $expenseEntriesCount;
        } else {
            $totalIncome = (float) (clone $baseQuery)->where('entry_type', 'INCOME')->sum('amount');
            $totalExpense = (float) (clone $baseQuery)->where('entry_type', 'EXPENSE')->sum('amount');
            $netBalance = $totalIncome - $totalExpense;
            $incomeEntriesCount = (clone $baseQuery)->where('entry_type', 'INCOME')->count();
            $expenseEntriesCount = (clone $baseQuery)->where('entry_type', 'EXPENSE')->count();
            $totalTransactions = $incomeEntriesCount + $expenseEntriesCount;
        }

        // Transactions Query for list and category breakdown (respecting type filter)
        $txQuery = (clone $baseQuery);
        if ($type === 'INCOME' || $type === 'EXPENSE') {
            $txQuery->where('entry_type', $type);
        }

        // Operational Breakdown & Category Distribution (Top 6 categories)
        $categoryBreakdown = (clone $txQuery)->select('category', 'entry_type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as entries_count'))
            ->groupBy('category', 'entry_type')
            ->orderByDesc('total_amount')
            ->take(6)
            ->get()
            ->map(function ($item) use ($totalExpense, $totalIncome) {
                $base = $item->entry_type === 'EXPENSE' ? ($totalExpense ?: 1) : ($totalIncome ?: 1);
                $item->percentage = min(100, round(($item->total_amount / $base) * 100));

                return $item;
            });

        // Recent transactions (limit to 50 for performance and smooth display)
        $transactions = (clone $txQuery)->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->take(50)->get();

        // Staff list for suggestions and filters
        $staffList = Admins::pluck('name')
            ->merge(FinanceTransaction::distinct()->pluck('staff_name'))
            ->unique()
            ->filter()
            ->values();

        // Investment & Capital Portfolio Metrics
        $totalInvested = (float) FinanceTransaction::where('entry_type', 'INCOME')
            ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
            ->sum('amount');

        $totalWithdrawn = (float) FinanceTransaction::where('entry_type', 'EXPENSE')
            ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
            ->sum('amount');

        $investmentBalance = $totalInvested - $totalWithdrawn;

        $investedCount = FinanceTransaction::where('entry_type', 'INCOME')
            ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
            ->count();

        $withdrawnCount = FinanceTransaction::where('entry_type', 'EXPENSE')
            ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
            ->count();

        $this->syncExistingInvestors();

        $registeredInvestors = Investor::orderBy('name')->get()->map(function ($inv) {
            $totalInvested = (float) $inv->transactions()->where('entry_type', 'INCOME')->sum('amount');
            $totalWithdrawn = (float) $inv->transactions()->where('entry_type', 'EXPENSE')->sum('amount');
            $inv->active_balance = max(0, $totalInvested - $totalWithdrawn);
            return $inv;
        });

        $investmentInvestors = $registeredInvestors->pluck('name')->values();

        // Global cumulative metrics for fund limits
        $globalTotalIncome = (float) FinanceTransaction::where('entry_type', 'INCOME')->sum('amount');
        $globalTotalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');
        $availableWorkingBalance = max(0, $globalTotalIncome - $globalTotalExpense);

        // Return JSON if AJAX requested (for dynamic table / metrics / breakdown refresh)
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'timeframe' => $timeframe,
                'metrics' => [
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                    'netBalance' => $netBalance,
                    'availableWorkingBalance' => $availableWorkingBalance,
                    'totalTransactions' => $totalTransactions,
                    'incomeEntriesCount' => $incomeEntriesCount,
                    'expenseEntriesCount' => $expenseEntriesCount,
                ],
                'investment' => [
                    'totalInvested' => $totalInvested,
                    'totalWithdrawn' => $totalWithdrawn,
                    'balance' => $investmentBalance,
                    'investedCount' => $investedCount,
                    'withdrawnCount' => $withdrawnCount,
                    'investors' => $investmentInvestors,
                    'registeredInvestors' => $registeredInvestors->map(function ($inv) {
                        return [
                            'id' => $inv->id,
                            'name' => $inv->name,
                            'balance' => (float) $inv->active_balance,
                            'phone' => $inv->phone,
                        ];
                    }),
                ],
                'categoryBreakdown' => $categoryBreakdown,
                'transactions' => $transactions->map(function ($tx) {
                    $hasReceipt = ! empty($tx->receipt_image) && file_exists(public_path('Uploads/finance/'.$tx->receipt_image));

                    return [
                        'id' => $tx->id,
                        'entry_type' => $tx->entry_type,
                        'amount' => (float) $tx->amount,
                        'category' => $tx->category,
                        'payment_method' => $tx->payment_method,
                        'staff_name' => $tx->staff_name,
                        'notes' => $tx->notes ?? '',
                        'has_receipt' => $hasReceipt,
                        'receipt_image' => $tx->receipt_image,
                        'receipt_url' => $hasReceipt ? asset('Uploads/finance/'.$tx->receipt_image) : '',
                        'date_formatted' => $tx->transaction_date ? $tx->transaction_date->format('M j, Y · g:i A') : ($tx->created_at ? $tx->created_at->format('M j, Y · g:i A') : 'N/A'),
                        'date_raw' => $tx->transaction_date ? $tx->transaction_date->format('Y-m-d\TH:i') : '',
                        'timestamp' => $tx->transaction_date ? $tx->transaction_date->timestamp : ($tx->created_at ? $tx->created_at->timestamp : 0),
                    ];
                }),
                'totalMatching' => $totalTransactions,
                'count' => $transactions->count(),
            ]);
        }

        return view('adminDash.finance.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'availableWorkingBalance',
            'totalTransactions',
            'incomeEntriesCount',
            'expenseEntriesCount',
            'categoryBreakdown',
            'staffList',
            'timeframe',
            'totalInvested',
            'totalWithdrawn',
            'investmentBalance',
            'investedCount',
            'withdrawnCount',
            'investmentInvestors',
            'registeredInvestors'
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

        // Validation: Expenses and Capital Withdrawals cannot exceed available funds/capital
        if ($validated['entry_type'] === 'EXPENSE') {
            $isCapitalWithdrawal = in_array($validated['category'], ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital']);

            // Calculate active capital
            $totalInvested = (float) FinanceTransaction::where('entry_type', 'INCOME')
                ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
                ->sum('amount');
            $totalWithdrawn = (float) FinanceTransaction::where('entry_type', 'EXPENSE')
                ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
                ->sum('amount');
            $activeCapital = max(0, $totalInvested - $totalWithdrawn);

            if ($isCapitalWithdrawal) {
                if ($validated['amount'] > $activeCapital) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Capital withdrawal amount (৳'.number_format($validated['amount'], 2).') cannot exceed active capital balance (৳'.number_format($activeCapital, 2).').',
                        'errors' => [
                            'amount' => ['Capital withdrawal cannot exceed active capital balance (৳'.number_format($activeCapital, 2).').'],
                        ],
                    ], 422);
                }
            } else {
                // Operating expenses: cannot exceed overall available working balance / capital
                $globalTotalIncome = (float) FinanceTransaction::where('entry_type', 'INCOME')->sum('amount');
                $globalTotalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');
                $availableBalance = max(0, $globalTotalIncome - $globalTotalExpense);

                if ($validated['amount'] > $availableBalance) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Expense amount of ৳'.number_format($validated['amount'], 2).' cannot exceed available capital/balance (৳'.number_format($availableBalance, 2).').',
                        'errors' => [
                            'amount' => ['Expense amount cannot exceed available capital/balance (৳'.number_format($availableBalance, 2).').'],
                        ],
                    ], 422);
                }
            }
        }

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
            'message' => ucfirst(strtolower($transaction->entry_type)).' entry of ৳'.number_format($transaction->amount, 2).' recorded successfully!',
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

        // Validation: Expenses and Capital Withdrawals cannot exceed available funds/capital (excluding current record)
        if ($validated['entry_type'] === 'EXPENSE') {
            $isCapitalWithdrawal = in_array($validated['category'], ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital']);

            // Calculate active capital excluding this transaction
            $totalInvested = (float) FinanceTransaction::where('id', '!=', $id)
                ->where('entry_type', 'INCOME')
                ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
                ->sum('amount');
            $totalWithdrawn = (float) FinanceTransaction::where('id', '!=', $id)
                ->where('entry_type', 'EXPENSE')
                ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
                ->sum('amount');
            $activeCapital = max(0, $totalInvested - $totalWithdrawn);

            if ($isCapitalWithdrawal) {
                if ($validated['amount'] > $activeCapital) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Capital withdrawal amount (৳'.number_format($validated['amount'], 2).') cannot exceed active capital balance (৳'.number_format($activeCapital, 2).').',
                        'errors' => [
                            'amount' => ['Capital withdrawal cannot exceed active capital balance (৳'.number_format($activeCapital, 2).').'],
                        ],
                    ], 422);
                }
            } else {
                // Operating expenses: cannot exceed overall available working balance / capital (excluding this transaction)
                $globalTotalIncome = (float) FinanceTransaction::where('id', '!=', $id)->where('entry_type', 'INCOME')->sum('amount');
                $globalTotalExpense = (float) FinanceTransaction::where('id', '!=', $id)->where('entry_type', 'EXPENSE')->sum('amount');
                $availableBalance = max(0, $globalTotalIncome - $globalTotalExpense);

                if ($validated['amount'] > $availableBalance) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Expense amount of ৳'.number_format($validated['amount'], 2).' cannot exceed available capital/balance (৳'.number_format($availableBalance, 2).').',
                        'errors' => [
                            'amount' => ['Expense amount cannot exceed available capital/balance (৳'.number_format($availableBalance, 2).').'],
                        ],
                    ], 422);
                }
            }
        } elseif ($validated['entry_type'] === 'INCOME') {
            // If reducing or changing income, ensure remaining income covers existing expenses
            $globalTotalIncomeAfter = (float) FinanceTransaction::where('id', '!=', $id)->where('entry_type', 'INCOME')->sum('amount') + $validated['amount'];
            $globalTotalExpense = (float) FinanceTransaction::where('id', '!=', $id)->where('entry_type', 'EXPENSE')->sum('amount');

            if ($globalTotalExpense > $globalTotalIncomeAfter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot reduce this income/capital entry to ৳'.number_format($validated['amount'], 2).' because existing expenses (৳'.number_format($globalTotalExpense, 2).') would exceed remaining funds.',
                    'errors' => [
                        'amount' => ['Amount cannot be less than required to cover existing expenses (৳'.number_format($globalTotalExpense, 2).').'],
                    ],
                ], 422);
            }
        }

        $receiptImageName = $transaction->receipt_image;
        if ($request->hasFile('receipt_image')) {
            // Delete old file if exists
            if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/'.$transaction->receipt_image))) {
                @unlink(public_path('Uploads/finance/'.$transaction->receipt_image));
            }
            $receiptImageName = $this->saveImageAsWebp($request->file('receipt_image'));
        } elseif ($request->input('remove_receipt') == '1') {
            if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/'.$transaction->receipt_image))) {
                @unlink(public_path('Uploads/finance/'.$transaction->receipt_image));
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
            'message' => 'Transaction #'.$transaction->id.' updated successfully!',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Remove the specified transaction from database.
     */
    public function destroy($id)
    {
        $transaction = FinanceTransaction::findOrFail($id);

        // If deleting income transaction, verify that expenses wouldn't exceed remaining income/capital
        if ($transaction->entry_type === 'INCOME') {
            $remainingIncome = (float) FinanceTransaction::where('id', '!=', $id)->where('entry_type', 'INCOME')->sum('amount');
            $totalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');

            if ($totalExpense > $remainingIncome) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete this income/capital entry because existing expenses (৳'.number_format($totalExpense, 2).') would exceed remaining capital/income (৳'.number_format($remainingIncome, 2).').',
                ], 422);
            }
        }

        // Remove receipt image from disk if exists
        if ($transaction->receipt_image && file_exists(public_path('Uploads/finance/'.$transaction->receipt_image))) {
            @unlink(public_path('Uploads/finance/'.$transaction->receipt_image));
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
            if ($request->category === 'ALL_INVESTMENTS') {
                $query->whereIn('category', ['Investment / Capital', 'Investment Withdrawal']);
            } else {
                $query->where('category', $request->category);
            }
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
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }
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
            } elseif ($timeframe === 'custom') {
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('transaction_date', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay(),
                    ]);
                } elseif ($request->filled('start_date')) {
                    $query->where('transaction_date', '>=', Carbon::parse($request->start_date)->startOfDay());
                } elseif ($request->filled('end_date')) {
                    $query->where('transaction_date', '<=', Carbon::parse($request->end_date)->endOfDay());
                }
            }
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $filename = 'finance_ledger_'.date('Y-m-d_His').'.csv';

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
                    '#FT-'.str_pad($tx->id, 5, '0', STR_PAD_LEFT),
                    $tx->entry_type,
                    $tx->amount,
                    $tx->category,
                    $tx->payment_method,
                    $tx->transaction_date ? $tx->transaction_date->format('Y-m-d H:i:s') : '',
                    $tx->staff_name,
                    $tx->notes ?? '',
                    $tx->receipt_image ? asset('Uploads/finance/'.$tx->receipt_image) : 'None',
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
        $timeframe = $request->get('timeframe', 'all');

        $query = FinanceTransaction::query();

        // 1. Filter: Timeframe
        if ($timeframe === 'today') {
            $query->whereDate('transaction_date', Carbon::today());
        } elseif ($timeframe === 'week') {
            $query->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($timeframe === 'month') {
            $query->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($timeframe === 'year') {
            $query->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } elseif ($timeframe === 'custom') {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('transaction_date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ]);
            } elseif ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', Carbon::parse($request->start_date)->startOfDay());
            } elseif ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', Carbon::parse($request->end_date)->endOfDay());
            }
        }

        // 2. Filter: Entry Type (Income / Expense)
        if ($request->filled('type') && in_array($request->type, ['INCOME', 'EXPENSE'])) {
            $query->where('entry_type', $request->type);
        }

        // 3. Filter: Category
        if ($request->filled('category') && $request->category !== 'ALL') {
            if ($request->category === 'ALL_INVESTMENTS') {
                $query->whereIn('category', ['Investment / Capital', 'Investment Withdrawal']);
            } else {
                $query->where('category', $request->category);
            }
        }

        // 4. Filter: Staff Name
        if ($request->filled('staff') && $request->staff !== 'ALL') {
            $query->where('staff_name', $request->staff);
        }

        // 5. Filter: Search Query
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

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(25)->withQueryString();

        $totalIncome = (float) FinanceTransaction::where('entry_type', 'INCOME')->sum('amount');
        $totalExpense = (float) FinanceTransaction::where('entry_type', 'EXPENSE')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $staffList = Admins::pluck('name')
            ->merge(FinanceTransaction::distinct()->pluck('staff_name'))
            ->unique()
            ->filter()
            ->values();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'html' => view('adminDash.finance.partials.history_rows', compact('transactions'))->render(),
                'pagination' => $transactions->hasPages() ? $transactions->links()->render() : '',
                'total' => $transactions->total(),
            ]);
        }

        return view('adminDash.finance.allTrans', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'staffList',
            'timeframe'
        ));
    }

    /**
     * Helper to convert and save any uploaded receipt image strictly as .webp format.
     */
    protected function saveImageAsWebp($file): string
    {
        $dir = public_path('Uploads/finance');
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'receipt_'.time().'_'.Str::random(8).'.webp';
        $fullPath = $dir.'/'.$fileName;

        try {
            $manager = new ImageManager(new Driver);
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

    /**
     * Helper to auto-sync any historical investor names from FinanceTransaction into investors table.
     */
    public function syncExistingInvestors(): void
    {
        $existingNames = FinanceTransaction::whereIn('category', [
            'Investment / Capital',
            'Investment Withdrawal',
            'Capital Withdrawal',
            'Investment',
            'Capital'
        ])->distinct()->pluck('staff_name')->filter()->values();

        foreach ($existingNames as $name) {
            $name = trim($name);
            if ($name && !Investor::where('name', $name)->exists()) {
                Investor::create([
                    'name' => $name,
                    'status' => 'active',
                    'notes' => 'Auto-synced from historical finance records.',
                ]);
            }
        }
    }

    /**
     * Display Investor Directory & Capital Portfolio Page.
     */
    public function investors(Request $request)
    {
        $this->syncExistingInvestors();

        $totalInvested = (float) FinanceTransaction::where('entry_type', 'INCOME')
            ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
            ->sum('amount');

        $totalWithdrawn = (float) FinanceTransaction::where('entry_type', 'EXPENSE')
            ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
            ->sum('amount');

        $investmentBalance = max(0, $totalInvested - $totalWithdrawn);
        $totalInvestorsCount = Investor::count();

        $query = Investor::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $allInvestors = $query->get()->map(function ($inv) use ($totalInvested) {
            $inv->total_invested = (float) $inv->transactions()->where('entry_type', 'INCOME')->sum('amount');
            $inv->total_withdrawn = (float) $inv->transactions()->where('entry_type', 'EXPENSE')->sum('amount');
            $inv->active_balance = $inv->total_invested - $inv->total_withdrawn;
            $inv->share_pct = $totalInvested > 0 ? round(($inv->total_invested / $totalInvested) * 100, 1) : 0;
            $inv->tx_count = $inv->transactions()->count();
            $inv->last_tx = $inv->transactions()->orderByDesc('transaction_date')->first();
            return $inv;
        });

        // Sorting
        $sort = $request->get('sort', 'highest_balance');
        if ($sort === 'highest_balance') {
            $allInvestors = $allInvestors->sortByDesc('active_balance')->values();
        } elseif ($sort === 'highest_invested') {
            $allInvestors = $allInvestors->sortByDesc('total_invested')->values();
        } elseif ($sort === 'most_transactions') {
            $allInvestors = $allInvestors->sortByDesc('tx_count')->values();
        } elseif ($sort === 'newest') {
            $allInvestors = $allInvestors->sortByDesc('created_at')->values();
        } elseif ($sort === 'name_asc') {
            $allInvestors = $allInvestors->sortBy('name')->values();
        }

        return view('adminDash.finance.investors', compact(
            'allInvestors',
            'totalInvested',
            'totalWithdrawn',
            'investmentBalance',
            'totalInvestorsCount',
            'sort'
        ));
    }

    /**
     * Register a new Investor / Partner with optional initial capital deposit.
     */
    public function storeInvestor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:investors,name',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'nid_or_passport' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'share_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'initial_investment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $investor = Investor::create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'nid_or_passport' => $validated['nid_or_passport'] ?? null,
            'address' => $validated['address'] ?? null,
            'share_percentage' => $validated['share_percentage'] ?? 0,
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        $tx = null;
        if (!empty($validated['initial_investment']) && $validated['initial_investment'] > 0) {
            $imageName = null;
            if ($request->hasFile('receipt_image')) {
                $imageName = $this->saveImageAsWebp($request->file('receipt_image'));
            }

            $tx = FinanceTransaction::create([
                'entry_type' => 'INCOME',
                'amount' => $validated['initial_investment'],
                'category' => 'Investment / Capital',
                'payment_method' => $validated['payment_method'] ?? 'Bank Transfer',
                'transaction_date' => Carbon::now(),
                'staff_name' => $investor->name,
                'notes' => 'Initial capital injection on onboarding. ' . ($validated['notes'] ?? ''),
                'receipt_image' => $imageName,
                'admin_id' => auth('admin')->id() ?? null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Partner '{$investor->name}' registered successfully!" . ($tx ? ' Initial capital of ৳' . number_format($tx->amount, 2) . ' deposited.' : ''),
            'investor' => $investor,
            'transaction' => $tx,
        ]);
    }

    /**
     * Return JSON data of investment history for an investor (for instant modal inspection).
     */
    public function investorHistoryAjax(Request $request, $id)
    {
        $investor = Investor::findOrFail($id);

        $transactions = $investor->transactions()
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        $totalInvested = (float) $transactions->where('entry_type', 'INCOME')->sum('amount');
        $totalWithdrawn = (float) $transactions->where('entry_type', 'EXPENSE')->sum('amount');
        $balance = $totalInvested - $totalWithdrawn;

        return response()->json([
            'status' => 'success',
            'investor' => [
                'id' => $investor->id,
                'name' => $investor->name,
                'phone' => $investor->phone ?? 'N/A',
                'email' => $investor->email ?? 'N/A',
                'address' => $investor->address ?? 'N/A',
                'status' => $investor->status,
                'notes' => $investor->notes ?? '',
                'created_at' => $investor->created_at ? $investor->created_at->format('M d, Y') : 'N/A',
            ],
            'metrics' => [
                'totalInvested' => $totalInvested,
                'totalWithdrawn' => $totalWithdrawn,
                'balance' => $balance,
                'count' => $transactions->count(),
                'formattedInvested' => '৳' . number_format($totalInvested, 2),
                'formattedWithdrawn' => '৳' . number_format($totalWithdrawn, 2),
                'formattedBalance' => '৳' . number_format($balance, 2),
            ],
            'transactions' => $transactions->map(function ($t) {
                $hasReceipt = !empty($t->receipt_image) && file_exists(public_path('Uploads/finance/' . $t->receipt_image));
                return [
                    'id' => $t->id,
                    'entry_type' => $t->entry_type,
                    'category' => $t->category,
                    'amount' => (float) $t->amount,
                    'formatted_amount' => '৳' . number_format($t->amount, 2),
                    'payment_method' => $t->payment_method,
                    'notes' => $t->notes ?? '',
                    'has_receipt' => $hasReceipt,
                    'receipt_url' => $hasReceipt ? asset('Uploads/finance/' . $t->receipt_image) : null,
                    'date_formatted' => $t->transaction_date ? $t->transaction_date->format('M d, Y · h:i A') : ($t->created_at ? $t->created_at->format('M d, Y · h:i A') : 'N/A'),
                ];
            }),
        ]);
    }

    /**
     * Dedicated Profile & Investment Ledger Page for an Investor.
     */
    public function investorDetail(Request $request, $id)
    {
        $investor = Investor::findOrFail($id);

        $portfolioTotalInvested = (float) FinanceTransaction::where('entry_type', 'INCOME')
            ->whereIn('category', ['Investment / Capital', 'Investment', 'Capital'])
            ->sum('amount');

        $portfolioTotalWithdrawn = (float) FinanceTransaction::where('entry_type', 'EXPENSE')
            ->whereIn('category', ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'])
            ->sum('amount');

        $portfolioBalance = max(0, $portfolioTotalInvested - $portfolioTotalWithdrawn);

        $transactions = $investor->transactions()
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $investorTotalInvested = (float) $investor->transactions()->where('entry_type', 'INCOME')->sum('amount');
        $investorTotalWithdrawn = (float) $investor->transactions()->where('entry_type', 'EXPENSE')->sum('amount');
        $investorBalance = $investorTotalInvested - $investorTotalWithdrawn;
        $investorSharePct = $portfolioTotalInvested > 0 ? round(($investorTotalInvested / $portfolioTotalInvested) * 100, 1) : 0;

        return view('adminDash.finance.investor_detail', compact(
            'investor',
            'transactions',
            'investorTotalInvested',
            'investorTotalWithdrawn',
            'investorBalance',
            'investorSharePct',
            'portfolioBalance'
        ));
    }
}
