@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- Hero + Produtos em Alta (frame "Container" 214:3) --}}
<section class="fm-hero">
    <div class="fm-container">
        <div class="fm-hero__grid">
            <div class="fm-hero__text-top">
                <h1 class="fm-heading-xl fm-hero__title">Cosméticos Naturais{{ "\n" }}Para Todo Tipo de Pele</h1>
                <p class="fm-body-lg fm-hero__subtitle">O Poder a África Para a sua Pele, Natural e Puro, rico em Antioxidantes, Hidrata e regenera, Feito para pele Africana.</p>
            </div>
            <div class="fm-hero__col-image">
                <img src="{{ asset('images/home/hero-banner.png') }}" alt="Modelo usando cosméticos Flor de Marula" class="fm-hero__image img-fluid">
            </div>
            <div class="fm-hero__actions">
                <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary">Comprar agora</a>
                <a href="{{ route('historia.index') }}" class="fm-btn fm-btn-outline">Nossa História</a>
            </div>
        </div>

        @if ($trendingProducts->isNotEmpty())
            <div class="fm-trend">
                <p class="fm-trend__title d-none d-lg-flex">Produtos{{ "\n" }}Em Alta</p>
                <p class="fm-trend__title d-flex d-lg-none">Produtos em Alta</p>
                <div class="fm-trend__cards flex-grow-1">
                    @foreach ($trendingProducts as $product)
                        <x-product-card :product="$product" variant="trend" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Faixa de beneficios (Section 1 / 214:60) + Garantia de 30 dias (Section 2 / 214:72)
     No mobile ficam empilhadas como sempre estiveram; no desktop (>=992px) o Figma
     "SEC.fig" mostra-as combinadas lado a lado dentro do mesmo cartao, por isso
     escondemos o par original no desktop e mostramos a versao combinada abaixo. --}}
<div class="d-lg-none">
    @include('partials.benefits-strip')

    <section class="fm-guarantee fm-guarantee--compact">
        <div class="fm-container">
            <div class="row align-items-center justify-content-center g-5 flex-nowrap">
                <div class="col-auto">
                    <div class="fm-guarantee__seal">
                        <img src="{{ asset('images/home/guarantee-seal.png') }}" alt="Selo de garantia de 30 dias" loading="lazy">
                        <div class="fm-guarantee__seal-text">
                            <span class="fm-guarantee__seal-days">30 DIAS</span>
                            <span class="fm-guarantee__seal-label">Garantia de devolução{{ "\n" }}do dinheiro</span>
                        </div>
                    </div>
                </div>
                <div class="col text-start">
                    <p class="fm-guarantee__promise-title mb-2">Nossa promessa</p>
                    <p class="fm-guarantee__promise-heading mb-2">Pele melhor em 30 dias</p>
                    <p class="fm-guarantee__promise-text mb-0">Não ficou satisfeito? Devolvemos o seu dinheiro em 30 dias.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="fm-featured-strip d-none d-lg-block">
    <div class="fm-container">
        <div class="fm-benefits-card fm-featured-strip__card">
            <div class="fm-featured-strip__icons">
                <div class="fm-featured-strip__icon-grid">
                    <div class="fm-benefits-card__item">
                        <img src="{{ asset('images/home/benefits/badge-leaf.png') }}" alt="" class="fm-benefits-card__icon">
                        <span>Sem Parabenhos</span>
                    </div>
                    <div class="fm-benefits-card__item">
                        <img src="{{ asset('images/home/benefits/badge-flask.png') }}" alt="" class="fm-benefits-card__icon">
                        <span>Sem Corantes</span>
                    </div>
                    <div class="fm-benefits-card__item">
                        <img src="{{ asset('images/home/benefits/badge-rabbit.png') }}" alt="" class="fm-benefits-card__icon">
                        <span>Não Testado em Animais</span>
                    </div>
                    <div class="fm-benefits-card__item">
                        <img src="{{ asset('images/home/benefits/badge-mortar.png') }}" alt="" class="fm-benefits-card__icon">
                        <span>Ingredientes de Origem Natural</span>
                    </div>
                </div>
                <div class="fm-benefits-card__africa">
                    <span class="fm-benefits-card__africa-ornament">✦</span>
                    <img src="{{ asset('images/home/benefits/badge-heart-africa-v2.png') }}" alt="" class="fm-benefits-card__africa-icon">
                    <span class="fm-benefits-card__africa-text">Orgulhosamente<strong>Africano</strong></span>
                    <span class="fm-benefits-card__africa-ornament">✦</span>
                </div>
            </div>
            <div class="fm-featured-strip__divider"></div>
            <div class="fm-featured-strip__guarantee">
                <div class="fm-guarantee__seal">
                    <img src="{{ asset('images/home/guarantee-seal.png') }}" alt="Selo de garantia de 30 dias" loading="lazy">
                    <div class="fm-guarantee__seal-text">
                        <span class="fm-guarantee__seal-days">30 DIAS</span>
                        <span class="fm-guarantee__seal-label">Garantia de devolução{{ "\n" }}do dinheiro</span>
                    </div>
                </div>
                <div class="text-start">
                    <p class="fm-guarantee__promise-title mb-2">Nossa promessa</p>
                    <p class="fm-guarantee__promise-heading mb-2">Pele melhor em 30 dias</p>
                    <p class="fm-guarantee__promise-text mb-0">Não ficou satisfeito? Devolvemos o seu dinheiro em 30 dias.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Nosso Melhor Produto (214:86) --}}
<section class="py-4 pt-lg-2 pb-lg-5 fm-best-product-section">
    <div class="fm-container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <h2 class="fm-heading-italiana fm-heading-split fm-best-product-title mb-0">Mais <span class="fm-accent-word">Vendido</span></h2>
            <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary fm-cta-label d-none d-lg-inline-flex">Ver Todos</a>
        </div>
        <div class="row g-4">
            @foreach ($bestProducts as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-product-card :product="$product" variant="best" />
                </div>
            @endforeach
        </div>
        <a href="{{ route('shop.index') }}" class="fm-btn fm-btn-primary fm-cta-label d-flex d-lg-none w-100 mt-4">Ver Todos</a>
    </div>
</section>

{{-- Depoimentos reais das redes sociais, em grelha tipo mosaico --}}
@if ($testimonials->isNotEmpty())
    <section class="fm-social-proof">
        <div class="fm-container">
            <h2 class="fm-heading-italiana fm-heading-split text-center mb-2">Mais de 17.000 clientes confiam na <span class="fm-accent-word">Flor de Marula</span></h2>
            <p class="fm-body-lg fm-social-proof__subtitle text-center mb-5">Depoimentos reais partilhados pelos nossos clientes nas redes sociais</p>
            <div class="fm-social-proof__grid">
                @foreach ($testimonials as $testimonial)
                    <div class="fm-social-proof__item">
                        <img
                            src="{{ asset($testimonial->image_path) }}"
                            alt="Depoimento de cliente Flor de Marula"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- A Diferenca dos Nossos Produtos (214:121) --}}
@include('partials.diff-section', ['homeHeadingStyle' => true])

{{-- Produtos Naturais — beneficios (secao escura, Secca 2 / 214:194) --}}
<section class="fm-benefits-dark">
    <div class="fm-container">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6">
                <img src="{{ asset('images/home/benefits-dark-photo.png') }}" alt="Ingredientes naturais Flor de Marula" class="fm-benefits-dark__image" loading="lazy">
            </div>
            <div class="col-12 col-lg-6">
                <div class="row g-4">
                    @php
                        $benefits = [
                            ['title' => 'Produtos Naturais', 'text' => "Nós garantimos a você que os nossos produtos são Feitos com ingredintes 100% naturais"],
                            ['title' => 'Sem Parapenhos', 'text' => 'Nós garantimos nenhuma toxina seja adicionada à sua pele linda'],
                            ['title' => 'Melanina Formulada', 'text' => 'Todos os nossos produtos são formulados especificamente para pele com melanina'],
                            ['title' => 'Sem Corantes', 'text' => 'Fórmula mais limpa e focada nos ingredientes essenciais.'],
                        ];
                    @endphp
                    @foreach ($benefits as $benefit)
                        <div class="col-12 col-md-6 text-center text-lg-start">
                            <p class="fm-benefits-dark__item-title mb-2">{{ $benefit['title'] }}</p>
                            <p class="fm-benefits-dark__item-text mb-0">{{ $benefit['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Por que somos Diferentes (Section 6 - why us / 214:211) --}}
<section class="fm-compare">
    <div class="fm-container">
        <h2 class="fm-heading-italiana fm-heading-split text-center mb-3">Por que somos <span class="fm-accent-word">Diferentes</span></h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="fm-compare-card">
                    <div class="fm-compare-card__header fm-compare-card__header--us">
                        <p class="fm-heading-katibeh mb-0">Flor de Marula</p>
                    </div>
                    <div class="fm-compare-card__media">
                        <span class="fm-compare-card__image-frame">
                            <img src="{{ asset('images/home/compare-flor-de-marula.jpg') }}" alt="Flor de Marula" class="fm-compare-card__image" loading="lazy">
                        </span>
                    </div>
                    <ul class="fm-compare-card__list">
                        @foreach (['Feito Por Nós', 'Ingredientes Naturais', 'Seguro durante a gravidez', 'Livre de químicos, parabenos', 'Pequeno Lote Fresco', 'Feito para Pele de Melanina', '3-5 minutos por dia'] as $item)
                            <li>
                                <img src="{{ asset('images/home/icon-check.png') }}" alt="">
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Clientes adoram a Flor de Marula (214:280) --}}
@include('partials.testimonials-section', ['homeHeadingStyle' => true])

@endsection
