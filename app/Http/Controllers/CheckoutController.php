<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
    ) {
    }

    public function index()
    {
        $cart = $this->cartService->currentCart();
        $items = $this->cartService->itemsWithProducts($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('cart_error', 'O seu carrinho está vazio.');
        }

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $this->cartService->subtotal($cart),
            'discount' => $this->cartService->discount($cart),
            'shippingMethods' => ShippingMethod::where('is_active', true)->orderBy('sort_order')->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cartService->currentCart();
        $items = $this->cartService->itemsWithProducts($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('cart_error', 'O seu carrinho está vazio.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'shipping_method' => ['required', Rule::exists('shipping_methods', 'id')->where('is_active', true)],
            'payment_method' => ['required', Rule::exists('payment_methods', 'id')->where('is_active', true)],
        ]);

        $shippingMethod = ShippingMethod::findOrFail($validated['shipping_method']);
        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method']);

        $order = $this->orderService->createFromCart($cart, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address_line' => $validated['address_line'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'shipping_method' => $shippingMethod->label,
            'shipping_cost' => $shippingMethod->cost,
            'payment_method' => $paymentMethod->label,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('order.show', $order)->with('order_success', true);
    }
}
