<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Cache::remember('shop.categories', now()->addHour(), function () {
                return Category::orderBy('sort_order')->get();
            });
        } catch (\Throwable $e) {
            $categories = null;
        }

        if (!$categories instanceof \Illuminate\Support\Collection) {
            Cache::forget('shop.categories');
            $categories = Category::orderBy('sort_order')->get();
        }

        $activeCategory = $request->string('categoria')->toString();
        $search = $request->string('busca')->toString();

        $products = Product::active()
            ->when($activeCategory, fn ($query) => $query->whereHas(
                'categories',
                fn ($q) => $q->where('slug', $activeCategory)
            ))
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', compact('products', 'categories', 'activeCategory', 'search'));
    }
}
