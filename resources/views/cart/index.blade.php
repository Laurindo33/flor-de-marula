@extends('layouts.app')

@section('title', 'Carrinho')

@section('content')

<section class="fm-cart">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">O Seu Carrinho</h1>

        @if (session('cart_success'))
            <div class="fm-alert fm-alert--success">{{ session('cart_success') }}</div>
        @endif
        @if (session('cart_error'))
            <div class="fm-alert fm-alert--error">{{ session('cart_error') }}</div>
        @endif

        @if ($items->isEmpty())
            <div class="fm-cart-empty">
                <p class="fm-body-md mb-4">O seu carrinho está vazio.</p>
                <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary">Continuar a Comprar</a>
            </div>
        @else
            <div class="fm-cart__grid">
                <div class="fm-cart__items">
                    @foreach ($items as $item)
                        <div class="fm-cart-item">
                            <img src="{{ asset($item->product->image_path) }}" alt="{{ $item->product->name }}" class="fm-cart-item__image">

                            <div class="fm-cart-item__info">
                                <a href="{{ route('product.show', $item->product) }}" class="fm-cart-item__name">{{ $item->product->name }}</a>
                                <p class="fm-cart-item__unit-price mb-0">{{ $item->formatted_unit_price }} / un.</p>
                            </div>

                            <form action="{{ route('cart.update', $item) }}" method="POST" class="fm-cart-item__quantity">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="fm-qty-btn" aria-label="Diminuir quantidade">−</button>
                                <span>{{ $item->quantity }}</span>
                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="fm-qty-btn" aria-label="Aumentar quantidade">+</button>
                            </form>

                            <p class="fm-cart-item__total mb-0">{{ $item->formatted_line_total }}</p>

                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="fm-cart-item__remove">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Remover {{ $item->product->name }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6h16Z"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="fm-cart__summary">
                    <h2 class="fm-heading-sm mb-3" style="font-size: 22px;">Resumo</h2>

                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="fm-cart-coupon">
                        @csrf
                        @if ($cart->coupon_code)
                            @method('DELETE')
                            <div class="fm-cart-coupon__applied">
                                <span>Cupom <strong>{{ $cart->coupon_code }}</strong> aplicado</span>
                                <button type="submit" formaction="{{ route('cart.coupon.remove') }}" formmethod="POST" class="fm-cart-coupon__remove">Remover</button>
                            </div>
                        @else
                            <label for="coupon-code" class="fm-cart-coupon__label">Cupom de desconto</label>
                            <div class="fm-cart-coupon__field">
                                <input type="text" name="code" id="coupon-code" placeholder="Insira o código">
                                <button type="submit" class="fm-btn fm-btn-outline">Aplicar</button>
                            </div>
                        @endif
                    </form>

                    <div class="fm-cart-totals">
                        <div class="fm-cart-totals__row">
                            <span>Subtotal</span>
                            <span>{{ number_format($subtotal, 0, ',', '.') }}kz</span>
                        </div>
                        @if ($discount > 0)
                            <div class="fm-cart-totals__row fm-cart-totals__row--discount">
                                <span>Desconto</span>
                                <span>-{{ number_format($discount, 0, ',', '.') }}kz</span>
                            </div>
                        @endif
                        <div class="fm-cart-totals__row fm-cart-totals__row--note">
                            <span>Entrega</span>
                            <span>Calculada no checkout</span>
                        </div>
                        <div class="fm-cart-totals__row fm-cart-totals__row--total">
                            <span>Total</span>
                            <span>{{ number_format($total, 0, ',', '.') }}kz</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="fm-btn fm-btn-primary w-100 justify-content-center">Finalizar Compra</a>
                    <a href="{{ route('shop.index') }}" class="fm-cart__continue">Continuar a Comprar</a>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
