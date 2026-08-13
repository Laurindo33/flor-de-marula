<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public const STATUSES = [
        'Pagamento pendente',
        'Pago',
        'Em preparação',
        'Enviado',
        'Em distribuição',
        'Entregue',
        'Cancelado',
        'Reembolsado',
    ];

    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $orders = Order::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
            'activeStatus' => $status,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items', 'statusHistory', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update(['status' => $validated['status']]);
        $order->statusHistory()->create([
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('admin_success', 'Estado do pedido atualizado.');
    }
}
