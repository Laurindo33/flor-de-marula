<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $salesToday = Order::whereDate('created_at', today())->sum('total');
        $salesThisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
        $ordersCount = Order::count();
        $newCustomers = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $averageTicket = $ordersCount > 0 ? (int) round(Order::avg('total')) : 0;

        $lowStockProducts = Product::whereColumn('stock', '<=', 'stock_minimo')->orderBy('stock')->get();

        $recentOrders = Order::latest()->take(5)->get();

        $bestSellers = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $pendingReviews = Review::where('status', 'Pendente')->count();

        return view('admin.dashboard', compact(
            'salesToday',
            'salesThisMonth',
            'ordersCount',
            'newCustomers',
            'averageTicket',
            'lowStockProducts',
            'recentOrders',
            'bestSellers',
            'pendingReviews',
        ));
    }
}
