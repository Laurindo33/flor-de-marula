@extends('layouts.app')

@section('title', 'Entrar')

@section('content')

<section class="fm-auth">
    <div class="fm-container">
        <div class="fm-auth-card">
            <h1 class="fm-heading-italiana mb-2">Entrar</h1>
            <p class="fm-body-md mb-4">Aceda à sua conta Flor de Marula.</p>

            @if ($errors->any())
                <div class="fm-alert fm-alert--error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST">
                @csrf
                <div class="fm-checkout-field">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="fm-checkout-field">
                    <label for="password">Palavra-passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="fm-auth-remember">
                    <label><input type="checkbox" name="remember"> Manter sessão iniciada</label>
                </div>
                <button type="submit" class="fm-btn fm-btn-primary w-100 justify-content-center">Entrar</button>
            </form>

            <p class="fm-auth-switch">Ainda não tem conta? <a href="{{ route('register') }}">Registe-se</a></p>
        </div>
    </div>
</section>

@endsection
