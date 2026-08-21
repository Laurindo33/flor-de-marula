@extends('layouts.app')

@section('title', 'Pedido ' . $order->order_number)

@section('content')

<section class="fm-order-confirmation">
    <div class="fm-container">
        <div class="fm-order-confirmation__header">
            <span class="fm-order-confirmation__icon">✓</span>
            <h1 class="fm-heading-italiana mb-2">Pedido Confirmado</h1>
            <p class="fm-body-md">Obrigado, {{ $order->customer_name }}! O seu pedido <strong>{{ $order->order_number }}</strong> foi recebido.</p>
        </div>

        <div class="fm-order-card">
            <div class="fm-order-card__row">
                <span>Estado do pedido</span>
                <span class="fm-order-status">{{ $order->status }}</span>
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
                <span>{{ collect([$order->address_line, $order->city, $order->province])->filter()->implode(', ') }}</span>
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
                    <span>Desconto{{ $order->coupon_code ? " ({$order->coupon_code})" : '' }}</span>
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

        <div class="fm-order-confirmation__actions">
            <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary">Continuar a Comprar</a>
            <a href="{{ route('home') }}" class="fm-btn fm-btn-outline">Voltar ao Início</a>
        </div>
    </div>
</section>

@endsection
