@forelse($transactions as $tx)
    <tr>
        <td class="px-3">
            <div class="font-weight-bold font-12 f-text-title">#FT-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="font-11 f-text-muted">{{ $tx->transaction_date ? $tx->transaction_date->format('M d, Y · h:i A') : $tx->created_at->format('M d, Y') }}</div>
        </td>
        <td>
            @if($tx->entry_type === 'INCOME')
                <span class="badge badge-pill badge-income font-11 px-2.5 py-1">
                    <i class="fa-solid fa-arrow-up font-9 mr-1"></i> Income
                </span>
            @else
                <span class="badge badge-pill badge-expense font-11 px-2.5 py-1">
                    <i class="fa-solid fa-arrow-down font-9 mr-1"></i> Expense
                </span>
            @endif
        </td>
        <td>
            <strong class="f-text-title font-13">{{ $tx->category }}</strong>
            @if($tx->notes)
                <div class="font-11 f-text-muted text-truncate" style="max-width: 220px;" title="{{ $tx->notes }}">
                    {{ $tx->notes }}
                </div>
            @endif
        </td>
        <td>
            <span class="badge-method">{{ $tx->payment_method }}</span>
        </td>
        <td>
            <span class="font-weight-bold font-14 {{ $tx->entry_type === 'INCOME' ? 'text-success' : 'text-danger' }}">
                {{ $tx->entry_type === 'INCOME' ? '+' : '-' }}৳{{ number_format($tx->amount, 2) }}
            </span>
        </td>
        <td>
            <span class="font-12 f-text-title font-weight-bold">{{ $tx->staff_name }}</span>
        </td>
        <td>
            @if($tx->receipt_image && file_exists(public_path('Uploads/finance/' . $tx->receipt_image)))
                <img src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}" alt="Receipt" class="tx-thumb-sm view-receipt-btn" data-src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}" title="Click to view full receipt">
            @else
                <span class="font-11 f-text-muted">None</span>
            @endif
        </td>
        <td class="text-right px-3">
            <button type="button" class="btn btn-sm btn-outline-info edit-tx-row-btn py-1 px-2" 
                    data-id="{{ $tx->id }}"
                    data-type="{{ $tx->entry_type }}"
                    data-category="{{ $tx->category }}"
                    data-method="{{ $tx->payment_method }}"
                    data-amount="{{ $tx->amount }}"
                    data-staff="{{ $tx->staff_name }}"
                    data-date="{{ $tx->transaction_date ? $tx->transaction_date->format('Y-m-d\TH:i') : '' }}"
                    data-notes="{{ $tx->notes ?? '' }}"
                    data-receipt="{{ $tx->receipt_image && file_exists(public_path('Uploads/finance/' . $tx->receipt_image)) ? asset('Uploads/finance/' . $tx->receipt_image) : '' }}"
                    title="Edit Transaction" 
                    style="border-radius: 6px;">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger delete-tx-row-btn py-1 px-2" data-id="{{ $tx->id }}" title="Delete Entry" style="border-radius: 6px;">
                <i class="fa-solid fa-trash-can font-11"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5">
            <i class="fa-solid fa-receipt f-text-muted mb-2 font-32"></i>
            <h6 class="font-weight-bold f-text-title">No transactions found</h6>
            <p class="font-12 f-text-muted mb-0">Try changing your search or filter options</p>
        </td>
    </tr>
@endforelse
