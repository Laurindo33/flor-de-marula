<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index()
    {
        $cart = $this->cartService->currentCart();
        $items = $this->cartService->itemsWithProducts($cart);

        return view('cart.index', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $this->cartService->subtotal($cart),
            'discount' => $this->cartService->discount($cart),
            'total' => $this->cartService->total($cart),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'offer_id' => ['nullable', 'exists:product_offers,id'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->is_out_of_stock) {
            return back()->with('cart_error', "{$product->name} está fora de estoque.");
        }

        if (! empty($validated['offer_id'])) {
            $offer = ProductOffer::where('id', $validated['offer_id'])
                ->where('product_id', $product->id)
                ->firstOrFail();

            $unitPrice = (int) round($offer->price / $offer->quantity);
            $this->cartService->addItem($product, $offer->quantity, $unitPrice);
        } else {
            $this->cartService->addItem($product, 1);
        }

        return back()->with('cart_success', "{$product->name} adicionado ao carrinho.");
    }

    public function addMany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        foreach (Product::whereIn('id', $validated['product_ids'])->get() as $product) {
            if (! $product->is_out_of_stock) {
                $this->cartService->addItem($product, 1);
            }
        }

        return redirect()->route('cart.index')->with('cart_success', 'Rotina adicionada ao carrinho.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->cartService->updateQuantity($cartItem, $validated['quantity']);

        return back();
    }

    public function remove(CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        $this->cartService->removeItem($cartItem);

        return back()->with('cart_success', 'Produto removido do carrinho.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $cart = $this->cartService->currentCart();

        if ($this->cartService->applyCoupon($cart, $validated['code'])) {
            return back()->with('cart_success', 'Cupom aplicado com sucesso.');
        }

        return back()->with('cart_error', 'Cupom inválido ou expirado.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cartService->removeCoupon($this->cartService->currentCart());

        return back();
    }

    private function authorizeCartItem(CartItem $cartItem): void
    {
        abort_unless($cartItem->cart_id === $this->cartService->currentCart()->id, 403);
    }
}
