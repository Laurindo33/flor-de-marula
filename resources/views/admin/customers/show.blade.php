@extends('admin.layout')

@section('title', $customer->name)

@section('content')

<a href="{{ route('admin.customers.index') }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm mb-3">← Voltar aos Clientes</a>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">{{ $customer->name }}</p>
            <p class="mb-1">{{ $customer->email }}</p>
            <p class="mb-1">{{ $customer->phone ?? 'Sem telefone' }}</p>
            <p class="mb-0">Cliente desde {{ $customer->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Endereços</p>
            @forelse ($customer->addresses as $address)
                <p class="mb-2"><strong>{{ $address->label }}</strong><br>{{ $address->address_line }}, {{ $address->city }}, {{ $address->province }}</p>
            @empty
                <p class="fm-admin-empty mb-0">Nenhum endereço guardado.</p>
            @endforelse
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Pedidos</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Pedido</th><th>Data</th><th>Estado</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse ($customer->orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td><span class="fm-badge fm-badge--neutral">{{ $order->status }}</span></td>
                                <td>{{ $order->formatted_total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="fm-admin-empty">Ainda sem pedidos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
