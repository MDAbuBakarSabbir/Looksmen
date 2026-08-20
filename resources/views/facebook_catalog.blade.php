{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title><![CDATA[{{ $webName }} - Product Feed]]></title>
        <link>{{ url('/') }}</link>
        <description><![CDATA[{{ $webDescription }}]]></description>

        @foreach ($products as $product)
            @php
                // Image handling
                $firstImg = $product->productImages->first()?->image;
                if (!empty($firstImg)) {
                    $primaryImage = asset('Uploads/' . $firstImg);
                } elseif (!empty($product->image)) {
                    $primaryImage = asset('Uploads/' . $product->image);
                } else {
                    $primaryImage = asset('frontend/assets/img/placeholder.jpg');
                }

                if (!preg_match('~^https?://~i', $primaryImage)) {
                    $primaryImage = url($primaryImage);
                }

                // Price handling
                $newPrice = (float) ($product->new_price ?? 0);
                $oldPrice = (float) ($product->old_price ?? 0);

                if ($oldPrice > $newPrice && $newPrice > 0) {
                    $regularPrice = $oldPrice;
                    $salePrice = $newPrice;
                } else {
                    $regularPrice = $newPrice > 0 ? $newPrice : $oldPrice;
                    $salePrice = null;
                }

                $cleanDesc = trim(strip_tags($product->description ?? ''));
                if (empty($cleanDesc)) {
                    $cleanDesc = $product->title;
                }
                $cleanDesc = \Illuminate\Support\Str::limit($cleanDesc, 4900, '...');

                $availability = ((int) $product->stock > 0 || (string) $product->stock === 'in_stock') ? 'in stock' : 'out of stock';
                $productUrl = route('ProductView', [$product->id, $product->slug]);
                $categoryName = $product->category->name ?? 'General';
            @endphp
            <item>
                <g:id>{{ $product->id }}</g:id>
                <g:title><![CDATA[{{ $product->title }}]]></g:title>
                <g:description><![CDATA[{{ $cleanDesc }}]]></g:description>
                <g:link>{{ $productUrl }}</g:link>
                <g:image_link>{{ $primaryImage }}</g:image_link>

                @if($product->productImages->count() > 1)
                    @foreach($product->productImages->skip(1)->take(5) as $additionalImg)
                        @if(!empty($additionalImg->image))
                            <g:additional_image_link>{{ url('Uploads/' . $additionalImg->image) }}</g:additional_image_link>
                        @endif
                    @endforeach
                @endif

                <g:brand><![CDATA[{{ $webName }}]]></g:brand>
                <g:condition>new</g:condition>
                <g:availability>{{ $availability }}</g:availability>
                <g:price>{{ number_format($regularPrice, 2, '.', '') }} BDT</g:price>

                @if($salePrice !== null)
                    <g:sale_price>{{ number_format($salePrice, 2, '.', '') }} BDT</g:sale_price>
                @endif

                <g:google_product_category><![CDATA[{{ $categoryName }}]]></g:google_product_category>
                <g:product_type><![CDATA[{{ $categoryName }}]]></g:product_type>
                <g:mpn>{{ !empty($product->code) ? $product->code : 'LOOKS-' . $product->id }}</g:mpn>
                <g:custom_label_0>{{ $availability === 'in stock' ? 'In Stock' : 'Out of Stock' }}</g:custom_label_0>
                <g:custom_label_1><![CDATA[{{ $categoryName }}]]></g:custom_label_1>
                @if(isset($product->flash_sale) && $product->flash_sale == '1')
                    <g:custom_label_2>Flash Sale</g:custom_label_2>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
