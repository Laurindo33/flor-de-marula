<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly InventoryService $inventoryService,
    ) {
    }

    /**
     * Cria o pedido a partir do carrinho. Todos os valores monetarios sao
     * recalculados aqui a partir dos dados do carrinho/produtos — nunca
     * confiamos em precos vindos do formulario de checkout.
     */
    public function createFromCart(Cart $cart, array $checkoutData): Order
    {
        return DB::transaction(function () use ($cart, $checkoutData) {
            $items = $this->cartService->itemsWithProducts($cart);

            $subtotal = $this->cartService->subtotal($cart);
            $discount = $this->cartService->discount($cart);
            $shippingCost = $checkoutData['shipping_cost'];
            $total = max(0, $subtotal - $discount) + $shippingCost;

            $order = Order::create([
                'user_id' => $checkoutData['user_id'] ?? null,
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $checkoutData['name'],
                'customer_email' => $checkoutData['email'],
                'customer_phone' => $checkoutData['phone'],
                'address_line' => $checkoutData['address_line'],
                'city' => $checkoutData['city'],
                'province' => $checkoutData['province'],
                'shipping_method' => $checkoutData['shipping_method'],
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $cart->coupon_code,
                'payment_method' => $checkoutData['payment_method'],
                'status' => 'Pagamento pendente',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->unit_price * $item->quantity,
                ]);

                $this->inventoryService->registerMovement(
                    $item->product,
                    'venda',
                    -$item->quantity,
                    "Pedido {$order->order_number}"
                );
            }

            $order->statusHistory()->create([
                'status' => 'Pagamento pendente',
                'note' => 'Pedido criado.',
            ]);

            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'FM-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
