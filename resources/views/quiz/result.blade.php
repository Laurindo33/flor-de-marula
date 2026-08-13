@extends('layouts.app')

@section('title', 'A Sua Rotina Flor de Marula')

@section('content')

<section class="fm-quiz-hero">
    <div class="fm-container text-center">
        <h1 class="fm-heading-italiana mb-2">A Sua Rotina Recomendada</h1>
        <p class="fm-body-lg">Com base na sua pele {{ Str::lower($answers['skin_type']) }}, preocupação com {{ Str::lower($answers['concern']) }} e objetivo de {{ Str::lower($answers['goal']) }}.</p>
    </div>
</section>

<section class="fm-quiz-result">
    <div class="fm-container">
        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-product-card :product="$product" variant="best" />
                </div>
            @endforeach
        </div>

        <div class="fm-quiz-result__actions">
            <form action="{{ route('cart.add.many') }}" method="POST">
                @csrf
                @foreach ($products as $product)
                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                @endforeach
                <button type="submit" class="fm-btn fm-btn-primary">Adicionar Rotina ao Carrinho</button>
            </form>
            <a href="{{ route('quiz.index') }}" class="fm-cart__continue">Refazer o Quiz</a>
        </div>

        <p class="fm-checkout__disclaimer">Esta recomendação é baseada nas suas respostas e não substitui uma avaliação dermatológica profissional.</p>
    </div>
</section>

@endsection
