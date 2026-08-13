@extends('layouts.app')

@section('title', 'Favoritos')

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
                <h2 class="fm-checkout-section__title">Favoritos</h2>

                @if ($favorites->isEmpty())
                    <p class="fm-body-md">Ainda não tem produtos favoritos. <a href="{{ route('shop.index') }}">Ir à loja</a></p>
                @else
                    <div class="row g-4">
                        @foreach ($favorites as $favorite)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <x-product-card :product="$favorite->product" variant="best" />
                                <form action="{{ route('favorites.destroy', $favorite->product) }}" method="POST" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fm-cart-coupon__remove">Remover dos favoritos</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
