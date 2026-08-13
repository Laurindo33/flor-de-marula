@extends('layouts.app')

@section('title', 'Minha Conta')

@section('content')

<section class="fm-account">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">Minha Conta</h1>

        @if (session('account_success'))
            <div class="fm-alert fm-alert--success">{{ session('account_success') }}</div>
        @endif

        <div class="fm-account__grid">
            @include('partials.account-nav')

            <div class="fm-account__content">
                <h2 class="fm-checkout-section__title">Perfil</h2>
                <form action="{{ route('account.profile.update') }}" method="POST" class="fm-account-form">
                    @csrf
                    <div class="fm-checkout-field">
                        <label for="name">Nome Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="fm-checkout-field">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="fm-checkout-field">
                        <label for="phone">Telefone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <button type="submit" class="fm-btn fm-btn-primary">Guardar Alterações</button>
                </form>

                <h2 class="fm-checkout-section__title fm-account__recent-title">Pedidos Recentes</h2>
                @if ($recentOrders->isEmpty())
                    <p class="fm-body-md">Ainda não tem pedidos. <a href="{{ route('shop.index') }}">Ir à loja</a></p>
                @else
                    <div class="fm-account-orders">
                        @foreach ($recentOrders as $order)
                            <a href="{{ route('account.orders.show', $order) }}" class="fm-account-order-row">
                                <span>{{ $order->order_number }}</span>
                                <span class="fm-order-status">{{ $order->status }}</span>
                                <span>{{ $order->formatted_total }}</span>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('account.orders') }}" class="fm-cart__continue">Ver todos os pedidos</a>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
