<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <!-- Homepage -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Pages -->
    @foreach ($pages as $page)
        <url>
            <loc>{{ route('pages', $page->slug) }}</loc>
            <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- Categories -->
    @foreach ($categories as $category)
        <url>
            <loc>{{ route('catProductView', [$category->id, $category->slug]) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- Subcategories -->
    @foreach ($subcategories as $subcategory)
        <url>
            <loc>{{ route('subCatProductView', [$subcategory->id, $subcategory->slug]) }}</loc>
            <lastmod>{{ $subcategory->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    <!-- Child Categories -->
    @foreach ($childcategories as $childcategory)
        <url>
            <loc>{{ route('childCatProductView', [$childcategory->id, $childcategory->slug]) }}</loc>
            <lastmod>{{ $childcategory->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    <!-- Products -->
    @foreach ($products as $product)
        <url>
            <loc>{{ route('ProductView', [$product->id, $product->slug]) }}</loc>
            <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

</urlset>
