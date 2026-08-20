<?php

namespace App\Http\Controllers;

use App\Models\GeneralWebSettings;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class FacebookCatalogController extends Controller
{
    /**
     * Generate dynamic XML Product Feed for Meta (Facebook) Catalog / Google Merchant.
     *
     * @return Response
     */
    public function index()
    {
        $feedContent = Cache::remember('facebook_catalog_xml_feed_v2', 900, function () {
            $webInfo = GeneralWebSettings::first();
            $webName = $webInfo->web_name ?? config('app.name', 'LOOKSMEN');
            $webDescription = strip_tags($webInfo->web_description ?? 'Official Product Catalog for ' . $webName);

            $products = Product::where('status', '1')
                ->with(['productImages', 'category'])
                ->orderBy('id', 'desc')
                ->get();

            $rendered = view('facebook_catalog', [
                'webName' => $webName,
                'webDescription' => $webDescription,
                'products' => $products,
            ])->render();

            return trim($rendered);
        });

        return response($feedContent, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=900');
    }

    /**
     * Generate dynamic CSV Product Feed for Meta (Facebook) Catalog.
     *
     * @return Response
     */
    public function csv()
    {
        $csvContent = Cache::remember('facebook_catalog_csv_feed_v2', 900, function () {
            $webInfo = GeneralWebSettings::first();
            $webName = $webInfo->web_name ?? config('app.name', 'LOOKSMEN');

            $products = Product::where('status', '1')
                ->with(['productImages', 'category'])
                ->orderBy('id', 'desc')
                ->get();

            $handle = fopen('php://temp', 'r+');

            // Standard Meta Commerce CSV Headers
            fputcsv($handle, [
                'id',
                'title',
                'description',
                'availability',
                'condition',
                'price',
                'sale_price',
                'link',
                'image_link',
                'additional_image_link',
                'brand',
                'google_product_category',
                'fb_product_category',
                'mpn',
            ]);

            foreach ($products as $product) {
                // Primary Image
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

                // Additional Images (comma-separated for CSV)
                $additionalImages = [];
                if ($product->productImages->count() > 1) {
                    foreach ($product->productImages->skip(1)->take(5) as $addImg) {
                        if (!empty($addImg->image)) {
                            $additionalImages[] = url('Uploads/' . $addImg->image);
                        }
                    }
                }
                $additionalImagesStr = implode(',', $additionalImages);

                // Price handling
                $newPrice = (float) ($product->new_price ?? 0);
                $oldPrice = (float) ($product->old_price ?? 0);

                if ($oldPrice > $newPrice && $newPrice > 0) {
                    $priceStr = number_format($oldPrice, 2, '.', '') . ' BDT';
                    $salePriceStr = number_format($newPrice, 2, '.', '') . ' BDT';
                } else {
                    $priceStr = number_format($newPrice > 0 ? $newPrice : $oldPrice, 2, '.', '') . ' BDT';
                    $salePriceStr = '';
                }

                // Clean description
                $rawDesc = $product->description ?? '';
                $cleanDesc = trim(strip_tags(html_entity_decode($rawDesc, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                $cleanDesc = preg_replace('/\s+/', ' ', $cleanDesc);
                if (empty($cleanDesc)) {
                    $cleanDesc = $product->title;
                }
                $cleanDesc = \Illuminate\Support\Str::limit($cleanDesc, 4900, '...');

                $availability = ((int) $product->stock > 0 || (string) $product->stock === 'in_stock') ? 'in stock' : 'out of stock';
                $productUrl = route('ProductView', [$product->id, $product->slug]);
                $categoryName = $product->category->name ?? 'General';
                $mpn = !empty($product->code) ? $product->code : 'LOOKS-' . $product->id;

                fputcsv($handle, [
                    $product->id,
                    $product->title,
                    $cleanDesc,
                    $availability,
                    'new',
                    $priceStr,
                    $salePriceStr,
                    $productUrl,
                    $primaryImage,
                    $additionalImagesStr,
                    $webName,
                    $categoryName,
                    $categoryName,
                    $mpn,
                ]);
            }

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return $content;
        });

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'inline; filename="facebook-catalog.csv"')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=900');
    }
}
