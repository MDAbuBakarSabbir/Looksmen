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
        $feedContent = Cache::remember('facebook_catalog_xml_feed_v1', 1800, function () {
            $webInfo = GeneralWebSettings::first();
            $webName = $webInfo->web_name ?? config('app.name', 'LOOKSMEN');
            $webDescription = strip_tags($webInfo->web_description ?? 'Official Product Catalog for ' . $webName);

            $products = Product::where('status', '1')
                ->with(['productImages', 'category'])
                ->orderBy('id', 'desc')
                ->get();

            return view('facebook_catalog', [
                'webName' => $webName,
                'webDescription' => $webDescription,
                'products' => $products,
            ])->render();
        });

        return response($feedContent, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=1800');
    }
}
