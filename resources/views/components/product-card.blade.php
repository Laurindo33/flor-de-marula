@props(['product', 'variant' => 'best'])

@if ($variant === 'trend')
    <div class="fm-trend-card">
        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="fm-trend-card__image">
        <div class="flex-grow-1">
            <p class="fm-product-card__name mb-1" style="font-size: 18px;">{{ $product->name }}</p>
            <p class="fm-price mb-1">{{ $product->formatted_price }}</p>
            @if ($product->discount_percent)
                <span class="fm-discount-badge">{{ $product->discount_percent }}% de desconto</span>
            @endif
        </div>
        <button type="button" class="fm-add-btn" aria-label="Adicionar {{ $product->name }} ao carrinho">+</button>
    </div>
@else
    <article class="fm-product-card">
        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="fm-product-card__image">
        <div class="fm-product-card__body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="fm-product-card__name mb-0">{{ $product->name }}</p>
                <p class="fm-price mb-0">{{ $product->formatted_price }}</p>
            </div>
            <a href="#" class="fm-btn fm-btn-outline fm-cta-label w-100 text-uppercase">Ver Detalhes</a>
        </div>
    </article>
@endif
