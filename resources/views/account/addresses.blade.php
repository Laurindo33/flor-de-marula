@extends('layouts.app')

@section('title', 'Meus Endereços')

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
                <h2 class="fm-checkout-section__title">Meus Endereços</h2>

                @if ($addresses->isNotEmpty())
                    <div class="fm-address-list">
                        @foreach ($addresses as $address)
                            <div class="fm-address-card">
                                <div>
                                    <p class="fm-address-card__label mb-1">{{ $address->label }}</p>
                                    <p class="mb-0">{{ $address->address_line }}, {{ $address->city }}, {{ $address->province }}</p>
                                </div>
                                <form action="{{ route('account.addresses.destroy', $address) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fm-cart-coupon__remove">Remover</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <h2 class="fm-checkout-section__title fm-account__recent-title">Adicionar Endereço</h2>
                <form action="{{ route('account.addresses.store') }}" method="POST" class="fm-account-form">
                    @csrf
                    <div class="fm-checkout-field">
                        <label for="label">Nome do Endereço</label>
                        <input type="text" name="label" id="label" placeholder="Ex.: Casa, Trabalho" required>
                    </div>
                    <div class="fm-checkout-field">
                        <label for="address_line">Endereço</label>
                        <input type="text" name="address_line" id="address_line" placeholder="Rua, número, bairro" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="fm-checkout-field">
                                <label for="city">Cidade / Município</label>
                                <input type="text" name="city" id="city" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="fm-checkout-field">
                                <label for="province">Província</label>
                                <input type="text" name="province" id="province" value="Luanda" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="fm-btn fm-btn-primary">Adicionar Endereço</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
