<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    public function currentCart(): Cart
    {
        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    public function addItem(Product $product, int $quantity, ?int $unitPrice = null): CartItem
    {
        $cart = $this->currentCart();
        $unitPrice ??= $product->price;

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('unit_price', $unitPrice)
            ->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);

            return $item;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $item->delete();

            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function applyCoupon(Cart $cart, string $code): bool
    {
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();

        if (! $coupon || ! $coupon->isValidFor($this->subtotal($cart))) {
            return false;
        }

        $cart->update(['coupon_code' => $coupon->code]);

        return true;
    }

    public function removeCoupon(Cart $cart): void
    {
        $cart->update(['coupon_code' => null]);
    }

    public function subtotal(Cart $cart): int
    {
        return $cart->items->sum(fn (CartItem $item) => $item->unit_price * $item->quantity);
    }

    public function discount(Cart $cart): int
    {
        if (! $cart->coupon_code) {
            return 0;
        }

        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($cart->coupon_code)])->first();
        $subtotal = $this->subtotal($cart);

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            return 0;
        }

        return $coupon->discountFor($subtotal);
    }

    public function total(Cart $cart): int
    {
        return max(0, $this->subtotal($cart) - $this->discount($cart));
    }

    public function itemCount(): int
    {
        $cart = Cart::where('session_id', session()->getId())->first();

        return $cart ? $cart->items->sum('quantity') : 0;
    }

    public function itemsWithProducts(Cart $cart): Collection
    {
        return $cart->items()->with('product')->get();
    }
}
