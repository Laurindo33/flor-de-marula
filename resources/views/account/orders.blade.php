@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')

<section class="fm-account">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">Minha Conta</h1>

        <div class="fm-account__grid">
            @include('partials.account-nav')

            <div class="fm-account__content">
                <h2 class="fm-checkout-section__title">Meus Pedidos</h2>

                @if ($orders->isEmpty())
                    <p class="fm-body-md">Ainda não tem pedidos. <a href="{{ route('shop.index') }}">Ir à loja</a></p>
                @else
                    <div class="fm-account-orders">
                        @foreach ($orders as $order)
                            <a href="{{ route('account.orders.show', $order) }}" class="fm-account-order-row">
                                <span>{{ $order->order_number }}</span>
                                <span>{{ $order->created_at->format('d/m/Y') }}</span>
                                <span class="fm-order-status">{{ $order->status }}</span>
                                <span>{{ $order->formatted_total }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
