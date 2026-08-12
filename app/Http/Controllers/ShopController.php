<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('sort_order')->get();

        return view('shop.index', compact('products'));
    }
}
