@extends('admin.layout')

@section('title', 'Pedidos')

@section('content')

<div class="fm-admin-filters">
    <a href="{{ route('admin.orders.index') }}" class="{{ $activeStatus === '' ? 'active' : '' }}">Todos</a>
    @foreach ($statuses as $status)
        <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="{{ $activeStatus === $status ? 'active' : '' }}">{{ $status }}</a>
    @endforeach
</div>

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>Pedido</th><th>Cliente</th><th>Data</th><th>Estado</th><th>Total</th><th></th></tr></thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td><span class="fm-badge fm-badge--neutral">{{ $order->status }}</span></td>
                        <td>{{ $order->formatted_total }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="fm-admin-empty">Nenhum pedido encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $orders->links() }}</div>
</div>

@endsection
