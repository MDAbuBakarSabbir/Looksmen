@php
    use App\Models\ProductImage;
@endphp

@forelse ($products as $product)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <input type="checkbox" class="product-check" value="{{ $product->id }}">
            </div>
        </td>
        <td>
            <span>{{ $product->title }}</span><br>
            Code: LM-{{ $product->code }}
        </td>
        @php
            $productImage = ProductImage::where('product_id', $product->id)->first();
        @endphp
        <td>
            @if (!$productImage)
                <img src="{{ asset('adminDash/images/product/demo.jpg') }}" alt="" style="height: 60px; width: 60px; object-fit: cover; border-radius: 6px;">
            @else
                <img src="{{ asset('adminDash/images/product/' . $productImage->image) }}" style="height: 60px; width: 60px; object-fit: cover; border-radius: 6px;" alt="">
            @endif
        </td>
        <td>
            Sale: {{ $product->num_of_sale ?? 0 }} pcs <br>
            Price: {{ $product->new_price }} BDT <br>
            Rating: {{ number_format($product->getAverageRating(), 2) }}
        </td>
        <td>
            @if($product->stock <= 0)
                <span class="badge badge-danger">Out of Stock</span>
            @elseif($product->stock < 10)
                <span class="badge badge-warning">Low Stock ({{ $product->stock }} PCS)</span>
            @else
                <span class="badge badge-success">{{ $product->stock }} PCS</span>
            @endif
        </td>
        <td>
            <label class="switch">
                <input class="todaysdeal-status" type="checkbox" data-id="{{ $product->id }}" {{ $product->todays_deal == '1' ? 'checked' : '' }}>
                <span class="slider round" title="{{ $product->todays_deal == '1' ? 'Click to Deactivate' : 'Click to Activate' }}"></span>
            </label>
        </td>
        <td>
            <label class="switch">
                <input class="product-status" type="checkbox" data-id="{{ $product->id }}" {{ $product->status == '1' ? 'checked' : '' }}>
                <span class="slider round" title="{{ $product->status == '1' ? 'Click to Deactivate' : 'Click to Activate' }}"></span>
            </label>
        </td>
        <td>
            <a title="View" href="{{ route('product.view', $product->id) }}" class="text-info mr-2">
                <i class="fa-solid fa-eye fa-lg"></i>
            </a>
            <a title="Edit" href="{{ route('product.edit', $product->id) }}" class="text-primary mr-2">
                <i class="fa-solid fa-pen-to-square fa-lg"></i>
            </a>
            <a title="Copy" href="javascript:void(0)" onclick="openCopyModal({{ $product->id }})" class="text-warning mr-2">
                <i class="fa-solid fa-copy fa-lg"></i>
            </a>
            <a title="Delete" onclick="deleteProduct({{ $product->id }})" href="javascript:void(0)" class="text-danger mr-2">
                <i class="fa-solid fa-trash fa-lg"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-danger font-weight-bold">No Products found matching filters.</td>
    </tr>
@endforelse
