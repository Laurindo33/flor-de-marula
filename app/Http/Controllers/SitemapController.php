<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticRoutes = [
            ['url' => route('home'), 'priority' => '1.0'],
            ['url' => route('shop.index'), 'priority' => '0.9'],
            ['url' => route('historia.index'), 'priority' => '0.6'],
            ['url' => route('ajuda.index'), 'priority' => '0.5'],
            ['url' => route('reviews.index'), 'priority' => '0.6'],
            ['url' => route('quiz.index'), 'priority' => '0.5'],
        ];

        $products = Product::active()->get()->map(fn (Product $product) => [
            'url' => route('product.show', $product),
            'priority' => '0.8',
            'lastmod' => $product->updated_at->toAtomString(),
        ]);

        $urls = collect($staticRoutes)->concat($products);

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
