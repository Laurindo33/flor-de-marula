@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<section class="fm-checkout">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">Finalizar Compra</h1>

        @if ($errors->any())
            <div class="fm-alert fm-alert--error">
                <p class="mb-1"><strong>Verifique os dados abaixo:</strong></p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="fm-checkout__grid">
            @csrf
            <div class="fm-checkout__form">
                <div class="fm-checkout-section">
                    <h2 class="fm-checkout-section__title">Dados Pessoais</h2>
                    <div class="fm-checkout-field">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="fm-checkout-field">
                        <label for="phone">Telefone</label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            placeholder="932005773"
                            inputmode="numeric"
                            pattern="[0-9]{9}"
                            maxlength="9"
                            title="Introduza os 9 dígitos do número de telefone"
                            required
                        >
                    </div>
                </div>

                <div class="fm-checkout-section">
                    <h2 class="fm-checkout-section__title">Endereço de Entrega</h2>
                    <div class="fm-checkout-field">
                        <label for="address_line">Endereço</label>
                        <input type="text" name="address_line" id="address_line" value="{{ old('address_line') }}" placeholder="Rua, número, bairro" required>
                    </div>
                    <div class="fm-checkout-field">
                        <label for="province">Província</label>
                        <select name="province" id="province" data-fm-province required>
                            <option value="Luanda" {{ old('province', 'Luanda') === 'Luanda' ? 'selected' : '' }}>Luanda</option>
                            <option value="Outro" {{ old('province') === 'Outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        <p class="fm-checkout__province-note" data-fm-province-note hidden>De momento, só fazemos entregas em Luanda.</p>
                    </div>
                </div>

                <div class="fm-checkout-section">
                    <h2 class="fm-checkout-section__title">Método de Entrega</h2>
                    <div class="fm-checkout-options">
                        @foreach ($shippingMethods as $method)
                            <label class="fm-checkout-option {{ $loop->first ? 'active' : '' }}">
                                <input type="radio" name="shipping_method" value="{{ $method->id }}" data-fm-shipping-cost="{{ $method->cost }}" {{ (int) old('shipping_method', $shippingMethods->first()?->id) === $method->id ? 'checked' : '' }}>
                                <span class="fm-checkout-option__label">{{ $method->label }}</span>
                                <span class="fm-checkout-option__price">{{ $method->cost > 0 ? number_format($method->cost, 0, ',', '.') . 'kz' : 'Grátis' }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="fm-checkout-section">
                    <h2 class="fm-checkout-section__title">Pagamento</h2>
                    <div class="fm-checkout-options">
                        @foreach ($paymentMethods as $method)
                            <label class="fm-checkout-option {{ $loop->first ? 'active' : '' }}">
                                <input type="radio" name="payment_method" value="{{ $method->id }}" {{ (int) old('payment_method', $paymentMethods->first()?->id) === $method->id ? 'checked' : '' }}>
                                <span class="fm-checkout-option__label">{{ $method->label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="fm-checkout__summary">
                <h2 class="fm-checkout-section__title">Resumo do Pedido</h2>

                <div class="fm-checkout-items">
                    @foreach ($items as $item)
                        <div class="fm-checkout-item">
                            <img src="{{ asset($item->product->image_path) }}" alt="{{ $item->product->name }}">
                            <div class="fm-checkout-item__info">
                                <p class="mb-0">{{ $item->product->name }}</p>
                                <span>Qtd. {{ $item->quantity }}</span>
                            </div>
                            <p class="fm-checkout-item__total mb-0">{{ $item->formatted_line_total }}</p>
                        </div>
                    @endforeach
                </div>

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
                    <div class="fm-cart-totals__row">
                        <span>Entrega</span>
                        <span data-fm-shipping-label>{{ number_format($shippingMethods->first()?->cost ?? 0, 0, ',', '.') }}kz</span>
                    </div>
                    <div class="fm-cart-totals__row fm-cart-totals__row--total">
                        <span>Total</span>
                        <span data-fm-order-total>{{ number_format(max(0, $subtotal - $discount) + ($shippingMethods->first()?->cost ?? 0), 0, ',', '.') }}kz</span>
                    </div>
                </div>

                <button type="submit" class="fm-btn fm-btn-primary fm-checkout__submit-btn w-100 justify-content-center" data-fm-checkout-submit>Finalizar Compra</button>
                <a href="{{ route('cart.index') }}" class="fm-cart__continue">Voltar ao Carrinho</a>

                <p class="fm-checkout__disclaimer">Os valores finais (subtotal, desconto e entrega) são sempre confirmados pelo servidor ao finalizar a compra.</p>
            </div>
        </form>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="shipping_method"]').forEach((input) => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.fm-checkout-option').forEach((el) => el.classList.remove('active'));
            input.closest('.fm-checkout-option').classList.add('active');

            const cost = Number(input.dataset.fmShippingCost);
            document.querySelector('[data-fm-shipping-label]').textContent = cost > 0
                ? cost.toLocaleString('pt-PT') + 'kz'
                : 'Grátis';

            const subtotal = {{ $subtotal }};
            const discount = {{ $discount }};
            const total = Math.max(0, subtotal - discount) + cost;
            document.querySelector('[data-fm-order-total]').textContent = total.toLocaleString('pt-PT') + 'kz';
        });
    });

    document.querySelectorAll('input[name="payment_method"]').forEach((input) => {
        input.addEventListener('change', () => {
            input.closest('.fm-checkout-section').querySelectorAll('.fm-checkout-option').forEach((el) => el.classList.remove('active'));
            input.closest('.fm-checkout-option').classList.add('active');
        });
    });

    const provinceSelect = document.querySelector('[data-fm-province]');
    const provinceNote = document.querySelector('[data-fm-province-note]');
    const checkoutSubmit = document.querySelector('[data-fm-checkout-submit]');

    if (provinceSelect && provinceNote && checkoutSubmit) {
        provinceSelect.addEventListener('change', () => {
            const isLuanda = provinceSelect.value === 'Luanda';
            provinceNote.hidden = isLuanda;
            checkoutSubmit.disabled = !isLuanda;
        });
        provinceSelect.dispatchEvent(new Event('change'));
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', () => {
            phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 9);
        });
    }
</script>
@endpush
