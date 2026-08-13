@extends('layouts.app')

@section('title', 'Criar Conta')

@section('content')

<section class="fm-auth">
    <div class="fm-container">
        <div class="fm-auth-card">
            <h1 class="fm-heading-italiana mb-2">Criar Conta</h1>
            <p class="fm-body-md mb-4">Junte-se à Flor de Marula.</p>

            @if ($errors->any())
                <div class="fm-alert fm-alert--error">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="fm-checkout-field">
                    <label for="name">Nome Completo</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="fm-checkout-field">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>
                <div class="fm-checkout-field">
                    <label for="phone">Telefone (opcional)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+244 9XX XXX XXX">
                </div>
                <div class="fm-checkout-field">
                    <label for="password">Palavra-passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="fm-checkout-field">
                    <label for="password_confirmation">Confirmar Palavra-passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
                <button type="submit" class="fm-btn fm-btn-primary w-100 justify-content-center">Criar Conta</button>
            </form>

            <p class="fm-auth-switch">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
        </div>
    </div>
</section>

@endsection
