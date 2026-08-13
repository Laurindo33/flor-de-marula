@extends('layouts.app')

@section('title', 'Pedido ' . $order->order_number)

@section('content')

<section class="fm-account">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">Minha Conta</h1>

        <div class="fm-account__grid">
            @include('partials.account-nav')

            <div class="fm-account__content">
                <a href="{{ route('account.orders') }}" class="fm-cart__continue" style="margin: 0 0 20px; text-align: left;">← Voltar aos pedidos</a>

                <h2 class="fm-checkout-section__title">Pedido {{ $order->order_number }}</h2>

                <div class="fm-order-card">
                    <div class="fm-order-card__row">
                        <span>Estado do pedido</span>
                        <span class="fm-order-status">{{ $order->status }}</span>
                    </div>
                    <div class="fm-order-card__row">
                        <span>Data</span>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="fm-order-card__row">
                        <span>Método de pagamento</span>
                        <span>{{ $order->payment_method }}</span>
                    </div>
                    <div class="fm-order-card__row">
                        <span>Entrega</span>
                        <span>{{ $order->shipping_method }}</span>
                    </div>
                    <div class="fm-order-card__row">
                        <span>Endereço</span>
                        <span>{{ $order->address_line }}, {{ $order->city }}, {{ $order->province }}</span>
                    </div>
                </div>

                <div class="fm-order-items">
                    @foreach ($order->items as $item)
                        <div class="fm-checkout-item">
                            <div class="fm-checkout-item__info">
                                <p class="mb-0">{{ $item->product_name }}</p>
                                <span>Qtd. {{ $item->quantity }} · {{ number_format($item->unit_price, 0, ',', '.') }}kz/un.</span>
                            </div>
                            <p class="fm-checkout-item__total mb-0">{{ $item->formatted_line_total }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="fm-cart-totals">
                    <div class="fm-cart-totals__row">
                        <span>Subtotal</span>
                        <span>{{ $order->formatted_subtotal }}</span>
                    </div>
                    @if ($order->discount > 0)
                        <div class="fm-cart-totals__row fm-cart-totals__row--discount">
                            <span>Desconto</span>
                            <span>-{{ $order->formatted_discount }}</span>
                        </div>
                    @endif
                    <div class="fm-cart-totals__row">
                        <span>Entrega</span>
                        <span>{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    <div class="fm-cart-totals__row fm-cart-totals__row--total">
                        <span>Total</span>
                        <span>{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
