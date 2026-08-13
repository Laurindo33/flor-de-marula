<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $trendingProducts = Product::active()->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        $bestProducts = Product::active()->where('is_best_seller', true)
            ->orderBy('sort_order')
            ->get();

        return view('home.index', compact('trendingProducts', 'bestProducts'));
    }
}
