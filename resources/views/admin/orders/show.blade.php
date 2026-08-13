@extends('admin.layout')

@section('title', 'Pedido ' . $order->order_number)

@section('content')

<a href="{{ route('admin.orders.index') }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm mb-3">← Voltar aos Pedidos</a>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Itens do Pedido</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Produto</th><th>Preço Unit.</th><th>Qtd.</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}kz</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->formatted_line_total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <p class="d-flex justify-content-between"><span>Subtotal</span><strong>{{ $order->formatted_subtotal }}</strong></p>
            @if ($order->discount > 0)
                <p class="d-flex justify-content-between"><span>Desconto {{ $order->coupon_code ? "({$order->coupon_code})" : '' }}</span><strong>-{{ $order->formatted_discount }}</strong></p>
            @endif
            <p class="d-flex justify-content-between"><span>Entrega</span><strong>{{ $order->formatted_shipping_cost }}</strong></p>
            <p class="d-flex justify-content-between fs-5"><span>Total</span><strong>{{ $order->formatted_total }}</strong></p>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Histórico de Estado</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Data</th><th>Estado</th><th>Nota</th></tr></thead>
                    <tbody>
                        @foreach ($order->statusHistory as $history)
                            <tr>
                                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="fm-badge fm-badge--neutral">{{ $history->status }}</span></td>
                                <td>{{ $history->note }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Cliente</p>
            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
            <p class="mb-1">{{ $order->customer_email }}</p>
            <p class="mb-1">{{ $order->customer_phone }}</p>
            @if ($order->user)
                <a href="{{ route('admin.customers.show', $order->user) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm mt-2">Ver Conta do Cliente</a>
            @endif
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Entrega e Pagamento</p>
            <p class="mb-1"><strong>Endereço:</strong> {{ $order->address_line }}, {{ $order->city }}, {{ $order->province }}</p>
            <p class="mb-1"><strong>Método de entrega:</strong> {{ $order->shipping_method }}</p>
            <p class="mb-0"><strong>Pagamento:</strong> {{ $order->payment_method }}</p>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Atualizar Estado</p>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="status">Novo Estado</label>
                    <select name="status" id="status" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fm-admin-field">
                    <label for="note">Nota (opcional)</label>
                    <input type="text" name="note" id="note">
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Atualizar Estado</button>
            </form>
        </div>
    </div>
</div>

@endsection
