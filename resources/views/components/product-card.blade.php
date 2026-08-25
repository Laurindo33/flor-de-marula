@props(['product', 'variant' => 'best'])

@if ($variant === 'trend')
    <div class="fm-trend-card {{ $product->is_out_of_stock ? 'fm-trend-card--out-of-stock' : '' }}">
        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="fm-trend-card__image">
        <div class="flex-grow-1">
            <p class="fm-trend-card__name mb-1">{{ $product->name }}</p>
            <p class="fm-price mb-1">{{ $product->formatted_price }}</p>
            @if ($product->is_out_of_stock)
                <span class="fm-stock-badge">Fora de Estoque</span>
            @endif
        </div>
        @if ($product->is_out_of_stock)
            <button type="button" class="fm-add-btn fm-add-btn--disabled" disabled aria-label="{{ $product->name }} fora de estoque">+</button>
        @else
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="fm-add-btn" aria-label="Adicionar {{ $product->name }} ao carrinho">+</button>
            </form>
        @endif
    </div>
@else
    <article class="fm-product-card {{ $product->is_out_of_stock ? 'fm-product-card--out-of-stock' : '' }}">
        <div class="fm-product-card__image-wrap">
            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="fm-product-card__image" loading="lazy">
            @if ($product->is_out_of_stock)
                <span class="fm-stock-badge fm-stock-badge--overlay">Fora de Estoque</span>
            @endif
        </div>
        <div class="fm-product-card__body">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <p class="fm-product-card__name mb-0">{{ $product->name }}</p>
                <p class="fm-price mb-0">{{ $product->formatted_price }}</p>
            </div>
            @if ($product->tagline)
                <p class="fm-product-card__tagline mb-3">{{ $product->tagline }}</p>
            @endif
            <a href="{{ route('product.show', $product) }}" class="fm-btn fm-btn-outline fm-cta-label w-100 text-uppercase">Ver Detalhes</a>
        </div>
    </article>
@endif
