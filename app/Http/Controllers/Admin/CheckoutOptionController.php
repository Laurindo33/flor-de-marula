<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutOptionController extends Controller
{
    public function index()
    {
        return view('admin.checkout-options.index', [
            'shippingMethods' => ShippingMethod::orderBy('sort_order')->get(),
            'paymentMethods' => PaymentMethod::orderBy('sort_order')->get(),
        ]);
    }

    public function storeShipping(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $request->validate([
                'label' => ['required', 'string', 'max:255'],
                'cost' => ['required', 'integer', 'min:0'],
                'sort_order' => ['nullable', 'integer'],
            ]);
            $validated['is_active'] = $request->boolean('is_active', true);

            ShippingMethod::create($validated);

            return back()->with('admin_success', 'Método de entrega criado com sucesso.');
        });
    }

    public function updateShipping(Request $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        return $this->safely(function () use ($request, $shippingMethod) {
            $validated = $request->validate([
                'label' => ['required', 'string', 'max:255'],
                'cost' => ['required', 'integer', 'min:0'],
                'sort_order' => ['nullable', 'integer'],
            ]);
            $validated['is_active'] = $request->boolean('is_active');

            $shippingMethod->update($validated);

            return back()->with('admin_success', 'Método de entrega atualizado.');
        });
    }

    public function destroyShipping(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->delete();

        return back()->with('admin_success', 'Método de entrega eliminado.');
    }

    public function storePayment(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $request->validate([
                'label' => ['required', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer'],
            ]);
            $validated['is_active'] = $request->boolean('is_active', true);

            PaymentMethod::create($validated);

            return back()->with('admin_success', 'Método de pagamento criado com sucesso.');
        });
    }

    public function updatePayment(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        return $this->safely(function () use ($request, $paymentMethod) {
            $validated = $request->validate([
                'label' => ['required', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer'],
            ]);
            $validated['is_active'] = $request->boolean('is_active');

            $paymentMethod->update($validated);

            return back()->with('admin_success', 'Método de pagamento atualizado.');
        });
    }

    public function destroyPayment(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return back()->with('admin_success', 'Método de pagamento eliminado.');
    }
}
