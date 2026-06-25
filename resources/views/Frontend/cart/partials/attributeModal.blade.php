

<div class="modal-header">
    <h6 class="modal-title fw-600">{{ $product->name }}</h6>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
    <input type="hidden" id="option_product_id" value="{{ $product->id }}">

    @if($product->productColors->count() > 0)
    <div class="mb-4">
        <label class="fw-600">Select Color:</label>
        <div class="d-flex flex-wrap">
            @foreach($product->productColors as $pc)
                <label class="mr-2 mb-2">
                    <input type="radio" name="color_id" value="{{ $pc->color_id }}" data-name="{{ $pc->color->color_name ?? 'N/A' }}" class="d-none color-input">
                    <span class="p-2 border rounded cursor-pointer color-box" onclick="$(this).prev().prop('checked', true); $('.color-box').css('border-color', 'transparent'); $(this).css('border-color', '#000');" title="{{ $pc->color->color_name ?? '' }}" style="background: {{ $pc->color->color_code ?? '#eee' }}; width: 35px; height: 35px; display: inline-block; border: 2px solid transparent;"></span>
                </label>
            @endforeach
        </div>
    </div>
    @endif

    @if($product->productAttributes->count() > 0)
        @foreach($product->productAttributes->groupBy('attribute_id') as $attr_id => $values)
        <div class="mb-3">
            <label class="fw-600">Select {{ $values->first()->attribute->name ?? 'Attribute' }}:</label>
            <select class="form-control attribute-select" data-id="{{ $attr_id }}">
                <option value="null" selected disabled>Select {{$values->first()->attribute->name}}</option>
                @foreach($values as $v)
                    <option value="{{ $v->attribute_value }}">{{ $v->attribute_value }}</option>
                @endforeach
            </select>
        </div>
        @endforeach
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary btn-block" onclick="confirmAddToCart()">Confirm</button>
</div>

