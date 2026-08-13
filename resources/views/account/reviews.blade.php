@extends('layouts.app')

@section('title', 'Minhas Avaliações')

@section('content')

<section class="fm-account">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-4">Minha Conta</h1>

        <div class="fm-account__grid">
            @include('partials.account-nav')

            <div class="fm-account__content">
                <h2 class="fm-checkout-section__title">Minhas Avaliações</h2>

                @if ($reviews->isEmpty())
                    <p class="fm-body-md">Ainda não enviou nenhuma avaliação. <a href="{{ route('reviews.index') }}">Avaliar um produto</a></p>
                @else
                    <div class="fm-account-orders">
                        @foreach ($reviews as $review)
                            <div class="fm-account-order-row">
                                <span>{{ $review->product->name }}</span>
                                <span>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                <span class="fm-order-status">{{ $review->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
