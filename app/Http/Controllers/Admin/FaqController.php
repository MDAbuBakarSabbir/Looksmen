<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Display a listing of the FAQs.
     */
    public function index(Request $request): View
    {
        $query = Faq::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $categories = Faq::categories();
        $totalFaqs = Faq::count();
        $activeFaqs = Faq::where('status', 1)->count();

        return view('adminDash.faq.index', compact('faqs', 'categories', 'totalFaqs', 'activeFaqs'));
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer'   => ['required', 'string'],
            'category' => ['required', 'string', 'max:50'],
            'order'    => ['nullable', 'integer'],
            'status'   => ['nullable'],
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
            'category' => $validated['category'],
            'order'    => $validated['order'] ?? 0,
            'status'   => $request->has('status') ? 1 : 0,
        ]);

        Cache::forget('frontend_active_faqs_list');

        return redirect()->route('admin.faq.index')->with('success', 'FAQ question created successfully!');
    }

    /**
     * Show the form for editing the specified FAQ via AJAX JSON.
     */
    public function edit(int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);

        return response()->json([
            'success' => true,
            'faq'     => $faq,
        ]);
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer'   => ['required', 'string'],
            'category' => ['required', 'string', 'max:50'],
            'order'    => ['nullable', 'integer'],
            'status'   => ['nullable'],
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
            'category' => $validated['category'],
            'order'    => $validated['order'] ?? 0,
            'status'   => $request->has('status') ? 1 : 0,
        ]);

        Cache::forget('frontend_active_faqs_list');

        return redirect()->route('admin.faq.index')->with('success', 'FAQ question updated successfully!');
    }

    /**
     * Toggle the active status of an FAQ via AJAX.
     */
    public function status(Request $request): JsonResponse
    {
        $faq = Faq::find($request->id);

        if (! $faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }

        $faq->status = $request->status == 1 ? 1 : 0;
        $faq->save();

        Cache::forget('frontend_active_faqs_list');

        return response()->json([
            'success' => true,
            'status'  => $faq->status,
            'message' => 'Status updated successfully!',
        ]);
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        Cache::forget('frontend_active_faqs_list');

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully!',
        ]);
    }
}
