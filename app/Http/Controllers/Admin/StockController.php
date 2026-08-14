<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService)
    {
    }

    public function index()
    {
        return view('admin.stock.index', [
            'products' => Product::orderBy('stock')->get(),
        ]);
    }

    public function movements(Product $product)
    {
        return view('admin.stock.movements', [
            'product' => $product,
            'movements' => $product->stockMovements()->get(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        return $this->safely(function () use ($request, $product) {
            $validated = $request->validate([
                'type' => ['required', 'in:entrada,venda,ajuste,cancelamento,devolucao'],
                'quantity' => ['required', 'integer', 'not_in:0'],
                'note' => ['nullable', 'string', 'max:255'],
            ]);

            $this->inventoryService->registerMovement(
                $product,
                $validated['type'],
                $validated['quantity'],
                $validated['note'] ?? null
            );

            return back()->with('admin_success', 'Movimento de stock registado.');
        });
    }
}
