@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- Hero + Produtos em Alta (frame "Container" 214:3) --}}
<section class="fm-hero">
    <div class="fm-container">
        <div class="fm-hero__grid row align-items-center gy-5">
            <div class="col-12 col-lg-6 fm-hero__col-text">
                <h1 class="fm-heading-xl fm-hero__title">Cosméticos Naturais{{ "\n" }}Para Todo Tipo de Pele</h1>
                <p class="fm-body-lg fm-hero__subtitle">O Poder a África Para a sua Pele, Natural e Puro, rico em Antioxidantes, Hidrata e regenera, Feito para pele Africana.</p>
                <div class="fm-hero__actions">
                    <a href="#" class="fm-btn fm-btn-primary">Comprar agora</a>
                    <a href="#" class="fm-btn fm-btn-outline">Nossa História</a>
                </div>
            </div>
            <div class="col-12 col-lg-6 fm-hero__col-image">
                <img src="{{ asset('images/home/hero-banner.png') }}" alt="Modelo usando cosméticos Flor de Marula" class="fm-hero__image img-fluid">
            </div>
        </div>

        @if ($trendingProducts->isNotEmpty())
            <div class="fm-trend">
                <p class="fm-trend__title d-none d-lg-flex">Produtos{{ "\n" }}Em Alta</p>
                <p class="fm-trend__title d-flex d-lg-none">Produtos em Alta</p>
                <div class="row g-3 flex-grow-1">
                    @foreach ($trendingProducts as $product)
                        <div class="col-12 col-md-6">
                            <x-product-card :product="$product" variant="trend" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Faixa de beneficios (Section 1 / 214:60) --}}
@include('partials.benefits-strip')

{{-- Garantia de 30 dias (Section 2 / 214:72) --}}
<section class="fm-guarantee">
    <div class="fm-container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-auto">
                <div class="fm-guarantee__seal">
                    <img src="{{ asset('images/home/guarantee-seal.png') }}" alt="Selo de garantia de 30 dias" loading="lazy">
                    <div class="fm-guarantee__seal-text">
                        <span class="fm-guarantee__seal-days">30 DIAS</span>
                        <span class="fm-guarantee__seal-label">Garantia de devolução{{ "\n" }}do dinheiro</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <p class="fm-guarantee__promise-title mb-2">Nossa promessa</p>
                <p class="fm-guarantee__promise-heading mb-2">Pele melhor em 30 dias</p>
                <p class="fm-guarantee__promise-text mb-0">Se você não ficar satisfeito com algum de nossos produtos, oferecemos uma garantia de reembolso de 30 dias.</p>
            </div>
        </div>
    </div>
</section>

{{-- Nosso Melhor Produto (214:86) --}}
<section class="py-5">
    <div class="fm-container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <h2 class="fm-heading-lg mb-0">Nosso Melhor Produto</h2>
            <a href="#" class="fm-btn fm-btn-primary fm-cta-label">Ver Todos</a>
        </div>
        <div class="row g-4">
            @foreach ($bestProducts as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-product-card :product="$product" variant="best" />
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- A Diferenca dos Nossos Produtos (214:121) --}}
@include('partials.diff-section')

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
                        <div class="col-12 col-md-6">
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
        <h2 class="fm-heading-italiana text-center mb-5">Por que somos Diferentes</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="fm-compare-card">
                    <div class="fm-compare-card__header fm-compare-card__header--us">
                        <p class="fm-heading-katibeh mb-0">Flor de Marula</p>
                    </div>
                    <img src="{{ asset('images/home/compare-flor-de-marula.jpg') }}" alt="Flor de Marula" class="fm-compare-card__image" loading="lazy">
                    <ul class="fm-compare-card__list">
                        @foreach (['Feito Por Nós', 'Ingredientes Naturais', 'Seguro durante a gravidez', 'Livre de químicos, parabenos', 'Pequeno Lote Fresco', 'Feito para Pele de Melanina'] as $item)
                            <li>
                                <img src="{{ asset('images/home/icon-check.png') }}" alt="">
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="fm-compare-card">
                    <div class="fm-compare-card__header fm-compare-card__header--them">
                        <p class="fm-heading-katibeh mb-0">Outros Produtos</p>
                    </div>
                    <img src="{{ asset('images/home/compare-outros-produtos.png') }}" alt="Outros produtos" class="fm-compare-card__image" loading="lazy">
                    <ul class="fm-compare-card__list">
                        @foreach (['Feito na China', 'SEM ingredientes naturais', 'NÃO é seguro para gravidez', 'NÃO químico, livre de parabenos', 'Grande Produção', 'Irrita a pele com melanina', 'Rotinas de 15 Min'] as $item)
                            <li>
                                <img src="{{ asset('images/home/icon-cross.png') }}" alt="">
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
@include('partials.testimonials-section')

@endsection
