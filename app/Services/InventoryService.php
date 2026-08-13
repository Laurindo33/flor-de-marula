<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Regista um movimento de stock e ajusta a quantidade do produto.
     * $quantity e' o delta: positivo para entrada/devolucao, negativo para venda/cancelamento.
     */
    public function registerMovement(Product $product, string $type, int $quantity, ?string $note = null): StockMovement
    {
        return DB::transaction(function () use ($product, $type, $quantity, $note) {
            $movement = $product->stockMovements()->create([
                'type' => $type,
                'quantity' => $quantity,
                'note' => $note,
            ]);

            $product->increment('stock', $quantity);

            return $movement;
        });
    }
}
