@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

<div class="fm-admin-kpis">
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Vendas Hoje</p>
        <p class="fm-admin-kpi__value mb-0">{{ number_format($salesToday, 0, ',', '.') }}kz</p>
    </div>
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Vendas do Mês</p>
        <p class="fm-admin-kpi__value mb-0">{{ number_format($salesThisMonth, 0, ',', '.') }}kz</p>
    </div>
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Pedidos</p>
        <p class="fm-admin-kpi__value mb-0">{{ $ordersCount }}</p>
    </div>
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Clientes Novos (mês)</p>
        <p class="fm-admin-kpi__value mb-0">{{ $newCustomers }}</p>
    </div>
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Ticket Médio</p>
        <p class="fm-admin-kpi__value mb-0">{{ number_format($averageTicket, 0, ',', '.') }}kz</p>
    </div>
    <div class="fm-admin-kpi">
        <p class="fm-admin-kpi__label mb-0">Avaliações Pendentes</p>
        <p class="fm-admin-kpi__value mb-0">{{ $pendingReviews }}</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Pedidos Recentes</p>
            @if ($recentOrders->isEmpty())
                <p class="fm-admin-empty mb-0">Ainda não há pedidos.</p>
            @else
                <div class="fm-admin-table-wrap">
                    <table class="fm-admin-table">
                        <thead><tr><th>Pedido</th><th>Cliente</th><th>Estado</th><th>Total</th></tr></thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td><span class="fm-badge fm-badge--neutral">{{ $order->status }}</span></td>
                                    <td>{{ $order->formatted_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Produtos Mais Vendidos</p>
            @if ($bestSellers->isEmpty())
                <p class="fm-admin-empty mb-0">Ainda sem vendas registadas.</p>
            @else
                <div class="fm-admin-table-wrap">
                    <table class="fm-admin-table">
                        <thead><tr><th>Produto</th><th>Unidades Vendidas</th></tr></thead>
                        <tbody>
                            @foreach ($bestSellers as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Produtos com Stock Baixo</p>
            @if ($lowStockProducts->isEmpty())
                <p class="fm-admin-empty mb-0">Nenhum produto com stock baixo.</p>
            @else
                <div class="fm-admin-table-wrap">
                    <table class="fm-admin-table">
                        <thead><tr><th>Produto</th><th>SKU</th><th>Stock Atual</th><th>Stock Mínimo</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td><span class="fm-badge fm-badge--danger">{{ $product->stock }}</span></td>
                                    <td>{{ $product->stock_minimo }}</td>
                                    <td><a href="{{ route('admin.stock.movements', $product) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Repor Stock</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
