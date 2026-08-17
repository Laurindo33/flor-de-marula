@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', $product->description)
@section('og_type', 'product')
@section('og_image', asset($product->image_path))

@section('content')

{{-- Faixa de beneficios (Secçã 2 / 213:1689) --}}
@include('partials.benefits-strip', ['sectionClass' => 'fm-benefits-strip--pdp'])

@php
    $mainImage = $product->image_path;
    $thumbnails = $product->images;
@endphp

{{-- Product details (155:996 "Product details" / Frame 41-77) --}}
<section class="fm-pdp">
    <div class="fm-container">
        <div class="fm-pdp__body">

            {{-- Miniaturas ("Other images" 161:1482-1486) --}}
            @if ($thumbnails->isNotEmpty())
                <div class="fm-pdp__thumbs order-2">
                    @foreach ($thumbnails as $thumb)
                        <button
                            type="button"
                            class="fm-pdp__thumb {{ $loop->first ? 'active' : '' }}"
                            data-fm-gallery-thumb
                            data-image="{{ asset($thumb->image_path) }}"
                        >
                            <img src="{{ asset($thumb->image_path) }}" alt="{{ $product->name }} — imagem {{ $loop->iteration }}">
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Imagem principal + garantia (Frame 77) --}}
            <div class="fm-pdp__visual order-1">
                <div class="fm-pdp__main-image {{ $product->is_out_of_stock ? 'fm-pdp__main-image--out-of-stock' : '' }}">
                    <img src="{{ asset($mainImage) }}" alt="{{ $product->name }}" data-fm-gallery-main>
                    @if ($product->is_out_of_stock)
                        <span class="fm-stock-badge fm-stock-badge--overlay">Fora de Estoque</span>
                    @endif
                </div>
            </div>

            {{-- Garantia (texto) + Complete Sua Rotina --}}
            <div class="fm-pdp__routine-block order-4">
                <div class="fm-pdp__guarantee-card">
                    <p class="fm-pdp__guarantee-card__title mb-3">Pele melhor em 30 dias ou é GRÁTIS</p>
                    <p class="fm-pdp__guarantee-card__text mb-0">Se você não estiver satisfeito com algum de nossos produtos, oferecemos garantia de devolução de dinheiro por 30 dias.</p>
                </div>

                @if ($product->routineProduct)
                    <div class="fm-pdp__routine">
                        <h2 class="fm-pdp__routine-title">Complete Sua Rotina</h2>
                        <p class="fm-pdp__routine-subtitle">A combinação perfeita de autocuidado que os usuários adoram com esse produto</p>
                        <div class="fm-pdp__routine-card">
                            <img src="{{ asset($product->routineProduct->image_path) }}" alt="{{ $product->routineProduct->name }}" loading="lazy">
                            <div class="flex-grow-1">
                                <p class="fm-pdp__routine-card-name mb-1">{{ $product->routineProduct->name }}</p>
                                <p class="mb-0">
                                    <span class="fm-price">{{ $product->routineProduct->formatted_price }}</span>
                                    @if ($product->routineProduct->compare_price)
                                        <span class="fm-pdp__routine-card-compare">{{ number_format($product->routineProduct->compare_price, 0, ',', '.') }}kz</span>
                                    @endif
                                </p>
                            </div>
                            <a
                                href="{{ route('product.show', $product->routineProduct) }}"
                                class="fm-btn fm-btn-primary fm-pdp__routine-cta {{ $product->routineProduct->is_out_of_stock ? 'fm-btn-primary--disabled' : '' }}"
                            >{{ $product->routineProduct->is_out_of_stock ? 'Fora de Estoque' : 'Comprar Agora' }}</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Nome, avaliacoes, preco, desconto, beneficios, ofertas, CTA, acordeao (Frame 75) --}}
            <div class="fm-pdp__info order-3">
                <h1 class="fm-pdp__name">{{ $product->name }}</h1>

                <div class="fm-pdp__avatars" aria-label="Avaliações de clientes">
                    @for ($i = 0; $i < 5; $i++)
                        <img src="{{ asset('images/product/icon-avatar.png') }}" alt="">
                    @endfor
                </div>

                <div class="fm-pdp__price">
                    @if ($product->compare_price)
                        <span class="fm-pdp__price-compare">{{ number_format($product->compare_price, 0, ',', '.') }}kz</span>
                    @endif
                    <span class="fm-pdp__price-current">{{ $product->formatted_price }}</span>
                </div>

                @if ($product->discount_percent)
                    <span class="fm-discount-badge fm-pdp__discount-badge">{{ $product->discount_percent }}% de desconto</span>
                @endif

                @if (!empty($product->benefits))
                    <ul class="fm-pdp__benefits">
                        @foreach ($product->benefits as $benefit)
                            <li>
                                <img src="{{ asset('images/product/icon-check.png') }}" alt="">
                                <span>{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="fm-pdp__add-form" data-fm-add-form>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="checkout" value="0" data-fm-checkout-flag>

                    @if ($product->offers->isNotEmpty())
                        <div class="fm-pdp__offers">
                            <p class="fm-pdp__offers-title">Ofertas exclusivas</p>
                            <div class="fm-pdp__offers-grid">
                                @foreach ($product->offers as $offer)
                                    @php
                                        $offerSavings = max(0, ($product->price * $offer->quantity) - $offer->price);
                                    @endphp
                                    <label
                                        class="fm-pdp__offer {{ $loop->first ? 'active' : '' }}"
                                        data-fm-offer
                                        data-offer-quantity="{{ $offer->quantity }}"
                                        data-offer-label="{{ $offer->label }}"
                                        data-offer-price-formatted="{{ $offer->formatted_price }}"
                                        data-offer-savings-formatted="{{ number_format($offerSavings, 0, ',', '.') }}kz"
                                    >
                                        <input type="radio" name="offer_id" value="{{ $offer->id }}" data-fm-offer-radio {{ $loop->first ? 'checked' : '' }}>
                                        <img src="{{ asset($offer->image_path ?? $product->image_path) }}" alt="{{ $offer->label }}">
                                        <span class="fm-pdp__offer-label">{{ $offer->label }}</span>
                                        <span class="fm-pdp__offer-price">{{ $offer->formatted_price }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($product->is_out_of_stock)
                        <button type="button" class="fm-btn fm-btn-primary fm-pdp__add-btn" disabled>Fora de Estoque</button>
                    @else
                        <button type="submit" class="fm-btn fm-btn-primary fm-pdp__add-btn">Comprar Agora</button>
                    @endif
                </form>

                @if ($product->offers->isNotEmpty())
                    <div class="modal fade" id="fmUpsellModal" tabindex="-1" aria-hidden="true" data-fm-upsell-modal>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content fm-upsell-modal">
                                <button type="button" class="btn-close fm-upsell-modal__close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                <div class="modal-body">
                                    <p class="fm-upsell-modal__title">Poupe ainda mais!</p>
                                    <div class="fm-upsell-modal__options" data-fm-upsell-options></div>
                                    <button type="button" class="fm-btn fm-btn-primary fm-upsell-modal__finish w-100 justify-content-center" data-fm-upsell-finish>Finalizar Compra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="accordion fm-pdp__accordion" id="fmProductAccordion">
                    @php
                        $accordionItems = collect([
                            ['title' => 'Descrição', 'body' => $product->description],
                            ['title' => 'Ingredientes', 'body' => $product->ingredients->pluck('name')->implode(', ') ?: $product->ingredients_list],
                            ['title' => 'Como usar', 'body' => $product->how_to_use],
                            ['title' => 'Avaliação de um especialista', 'body' => $product->expert_review],
                            ['title' => 'Envio e Devoluções', 'body' => $product->shipping_returns],
                        ])->filter(fn ($item) => filled($item['body']));
                    @endphp
                    @foreach ($accordionItems as $item)
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#fmAcc{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $item['title'] }}
                                </button>
                            </h3>
                            <div id="fmAcc{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#fmProductAccordion">
                                <div class="accordion-body">{{ $item['body'] }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if ($product->faqs->isNotEmpty())
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fmAccFaq">
                                    Perguntas Frequentes
                                </button>
                            </h3>
                            <div id="fmAccFaq" class="accordion-collapse collapse" data-bs-parent="#fmProductAccordion">
                                <div class="accordion-body">
                                    @foreach ($product->faqs as $faq)
                                        <p class="fm-pdp__faq-question mb-1">{{ $faq->question }}</p>
                                        <p class="fm-pdp__faq-answer {{ $loop->last ? 'mb-0' : 'mb-3' }}">{{ $faq->answer }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Produtos Relacionados (Secção Nosso melhor / Frame 5-9) --}}
@if ($product->relatedProducts->isNotEmpty())
    <section class="py-5 fm-best-product-section fm-pdp-related">
        <div class="fm-container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <h2 class="fm-heading-italiana fm-heading-split fm-best-product-title mb-0">Produtos <span class="fm-accent-word">Relacionados</span></h2>
                <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary fm-cta-label d-none d-lg-inline-flex">Ver Todos</a>
            </div>
            <div class="row g-4">
                @foreach ($product->relatedProducts as $related)
                    <div class="col-12 col-md-6 col-lg-4">
                        <x-product-card :product="$related" variant="best" />
                    </div>
                @endforeach
            </div>
            <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary fm-cta-label d-flex d-lg-none w-100 mt-4">Ver Todos</a>
        </div>
    </section>
@endif

{{-- Ingredientes (Frame 42 / 84-85) --}}
@if ($product->ingredients->isNotEmpty() || $product->ingredients_list)
    <section class="fm-pdp-ingredients">
        <div class="fm-container">
            <div class="row g-5">
                <div class="col-12 col-lg-5">
                    <h2 class="fm-heading-instrument mb-3">Feito com ingredientes 100% naturais</h2>
                    @if ($product->ingredients_list)
                        <p class="fm-pdp-ingredients__list">{{ $product->ingredients_list }}</p>
                    @endif
                </div>
                <div class="col-12 col-lg-7">
                    <div class="d-flex flex-column gap-4">
                        @foreach ($product->ingredients as $ingredient)
                            <div class="fm-pdp-ingredients__card">
                                <img src="{{ asset($ingredient->image_path) }}" alt="{{ $ingredient->name }}" loading="lazy">
                                <div>
                                    <p class="fm-pdp-ingredients__card-name mb-1">{{ $ingredient->name }}</p>
                                    <p class="fm-pdp-ingredients__card-desc mb-0">{{ $ingredient->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- A Diferenca dos Nossos Produtos (Secção Nosso melhor) --}}
@include('partials.diff-section', ['homeHeadingStyle' => true, 'sectionClass' => 'fm-diff-section--pdp'])

{{-- Clientes adoram a Flor de Marula (Secção Nosso melhor) --}}
@include('partials.testimonials-section', ['homeHeadingStyle' => true, 'sectionClass' => 'fm-testimonials--pdp'])

@endsection

@push('scripts')
    @vite(['resources/js/product.js'])

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description,
            'sku' => $product->sku,
            'image' => asset($product->image_path),
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'AOA',
                'price' => $product->price,
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('product.show', $product),
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush
