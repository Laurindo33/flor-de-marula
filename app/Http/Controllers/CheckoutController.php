<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public const SHIPPING_METHODS = [
        'luanda' => ['label' => 'Entrega em Luanda (24-48 horas úteis)', 'cost' => 2000],
        'provincia' => ['label' => 'Entrega noutras províncias (3-5 dias úteis)', 'cost' => 5000],
        'levantamento' => ['label' => 'Levantamento na loja (Talatona)', 'cost' => 0],
    ];

    public const PAYMENT_METHODS = [
        'entrega' => 'Pagamento na Entrega',
        'transferencia' => 'Transferência Bancária',
    ];

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
            'shippingMethods' => self::SHIPPING_METHODS,
            'paymentMethods' => self::PAYMENT_METHODS,
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
            'shipping_method' => ['required', Rule::in(array_keys(self::SHIPPING_METHODS))],
            'payment_method' => ['required', Rule::in(array_keys(self::PAYMENT_METHODS))],
        ]);

        $shippingMethod = self::SHIPPING_METHODS[$validated['shipping_method']];

        $order = $this->orderService->createFromCart($cart, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address_line' => $validated['address_line'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'shipping_method' => $shippingMethod['label'],
            'shipping_cost' => $shippingMethod['cost'],
            'payment_method' => self::PAYMENT_METHODS[$validated['payment_method']],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('order.show', $order)->with('order_success', true);
    }
}
