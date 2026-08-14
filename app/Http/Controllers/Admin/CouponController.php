<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $this->validateCoupon($request);

            Coupon::create($validated);

            return back()->with('admin_success', 'Cupom criado com sucesso.');
        });
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        return $this->safely(function () use ($request, $coupon) {
            $validated = $this->validateCoupon($request, $coupon);

            $coupon->update($validated);

            return back()->with('admin_success', 'Cupom atualizado.');
        });
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return back()->with('admin_success', 'Cupom eliminado.');
    }

    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $coupon?->id],
            'type' => ['required', 'in:percentual,fixo'],
            'value' => ['required', 'integer', 'min:1'],
            'min_order_value' => ['nullable', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['active'] = $request->boolean('active');

        return $validated;
    }
}
