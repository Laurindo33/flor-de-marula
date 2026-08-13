<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['images', 'ingredients', 'offers', 'faqs', 'routineProduct', 'relatedProducts']);

        return view('product.show', compact('product'));
    }
}
