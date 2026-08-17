@extends('layouts.app')

@section('title', 'Clientes Satisfeitos')
@section('meta_description', 'Veja o que os clientes da Flor de Marula dizem: avaliações, fotos e resultados reais.')

@section('content')

<section class="fm-reviews-hero">
    <div class="fm-container">
        <h1 class="fm-heading-lg text-center mb-2">+17.000 Clientes adoram a Flor de Marula</h1>
        <p class="fm-body-lg text-center">Veja o que alguns deles têm a dizer sobre seu produto favorito</p>
    </div>
</section>

<section class="fm-reviews">
    <div class="fm-container">
        <div class="fm-shop-filters">
            @foreach (['Todos', 'Antes e Depois', 'Fotos', 'Vídeos', 'Avaliações'] as $type)
                <a href="{{ route('reviews.index', $type === 'Todos' ? [] : ['tipo' => $type]) }}" class="fm-chip {{ $activeType === $type ? 'active' : '' }}">{{ $type }}</a>
            @endforeach
        </div>

        @if ($reviews->isEmpty())
            <p class="fm-body-md">Ainda não há avaliações nesta categoria.</p>
        @else
            <div class="fm-review-grid">
                @foreach ($reviews as $review)
                    <div class="fm-review-card">
                        @if ($review->before_photo && $review->after_photo)
                            <div class="fm-review-card__media fm-review-card__media--split">
                                <img src="{{ asset('storage/' . $review->before_photo) }}" alt="Antes" loading="lazy">
                                <img src="{{ asset('storage/' . $review->after_photo) }}" alt="Depois" loading="lazy">
                                <span class="fm-pill fm-pill--dark">Antes</span>
                                <span class="fm-pill fm-pill--light" style="left: auto; right: 12px;">Depois</span>
                            </div>
                        @elseif ($review->video_url)
                            <a href="{{ $review->video_url }}" target="_blank" rel="noopener" class="fm-review-card__media fm-review-card__video">
                                <span>▶ Ver vídeo</span>
                            </a>
                        @elseif ($review->before_photo)
                            <div class="fm-review-card__media">
                                <img src="{{ asset('storage/' . $review->before_photo) }}" alt="Foto de {{ $review->customer_name }}" loading="lazy">
                            </div>
                        @endif

                        <div class="fm-review-card__body">
                            <p class="fm-review-card__stars mb-1">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                            <p class="fm-review-card__name mb-1">{{ $review->customer_name }}</p>
                            <p class="fm-review-card__product mb-2">{{ $review->product->name }}</p>
                            @if ($review->comment)
                                <p class="fm-quote">&ldquo;{{ $review->comment }}&rdquo;</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="fm-review-form-section">
    <div class="fm-container">
        <h2 class="fm-heading-sm text-center mb-4">Envie a Sua Avaliação</h2>

        @if (session('review_success'))
            <div class="fm-alert fm-alert--success">{{ session('review_success') }}</div>
        @endif

        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="fm-review-form">
            @csrf
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="fm-checkout-field">
                        <label for="customer_name">Nome</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="fm-checkout-field">
                        <label for="product_id">Produto</label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Selecione um produto</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="fm-checkout-field">
                <label for="rating">Avaliação</label>
                <select name="rating" id="rating" required>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                    @endfor
                </select>
            </div>

            <div class="fm-checkout-field">
                <label for="comment">Comentário</label>
                <textarea name="comment" id="comment" rows="4">{{ old('comment') }}</textarea>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <div class="fm-checkout-field">
                        <label for="before_photo">Foto Antes (opcional)</label>
                        <input type="file" name="before_photo" id="before_photo" accept="image/*">
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="fm-checkout-field">
                        <label for="after_photo">Foto Depois (opcional)</label>
                        <input type="file" name="after_photo" id="after_photo" accept="image/*">
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="fm-checkout-field">
                        <label for="video_url">Link do Vídeo (opcional)</label>
                        <input type="url" name="video_url" id="video_url" placeholder="https://...">
                    </div>
                </div>
            </div>

            <button type="submit" class="fm-btn fm-btn-primary">Enviar Avaliação</button>
            <p class="fm-checkout__disclaimer">A sua avaliação será publicada após aprovação da nossa equipa.</p>
        </form>
    </div>
</section>

@endsection
