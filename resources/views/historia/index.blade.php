@extends('layouts.app')

@section('title', 'Nossa História')
@section('meta_description', 'Conheça a história da Flor de Marula, inspirada no poder do óleo de marula e na tradição africana de cuidado com a pele.')

@section('content')

{{-- Hero "Sobre Nós" (Frame 22 / 186:3) --}}
<section class="fm-historia-hero">
    <div class="fm-container">
        <div class="fm-historia-hero__grid">
            <div class="fm-historia-hero__text">
                <h1 class="fm-heading-xl">Sobre Nós</h1>
                <p class="fm-body-lg">Beleza inspirada na natureza. Cuidado pensado para a sua pele.</p>
                <p class="fm-historia-hero__paragraph">Na Flor de Marula, acreditamos que cuidar da pele vai muito além da estética. É um gesto diário de autoestima, confiança e bem-estar.{{ "\n\n" }}Inspirados no poder do óleo de marula, um dos ingredientes naturais mais valorizados do continente africano, desenvolvemos uma linha de cosméticos que combina tradição, natureza e inovação para oferecer um cuidado eficaz e delicado.</p>
                <img src="{{ asset('images/historia/hero.png') }}" alt="Maruleira — árvore de onde é extraído o óleo de marula" class="fm-historia-hero__image d-lg-none">
                <a href="{{ route('ajuda.index') }}" class="fm-btn fm-btn-primary">Contactos</a>
            </div>
            <img src="{{ asset('images/historia/hero.png') }}" alt="Maruleira — árvore de onde é extraído o óleo de marula" class="fm-historia-hero__image d-none d-lg-block">
        </div>
    </div>
</section>

{{-- Nossa História (Secçã 2 / 188:335) --}}
<section class="fm-historia-body">
    <div class="fm-container">
        <div class="fm-historia-body__grid">
            <div class="fm-historia-body__text">
                <h2 class="fm-heading-md">Nossa História</h2>
                <p class="fm-body-md fm-historia-body__lead">O poder da África para a sua pele.</p>
                <p class="fm-historia-body__paragraph">A história da Flor de Marula nasceu da admiração por uma das árvores mais emblemáticas da África: a Maruleira.{{ "\n\n" }}Durante gerações, o óleo extraído do seu fruto foi utilizado por comunidades africanas devido às suas propriedades hidratantes e ao seu rico conteúdo em antioxidantes e vitaminas.{{ "\n\n" }}Inspirados pela tradição africana, criamos a Flor de Marula para transformar ingredientes naturais em cosméticos de alta qualidade. Continuamos desenvolvendo fórmulas que unem natureza, inovação e simplicidade para oferecer um cuidado premium e valorizar a beleza natural da sua pele.</p>
            </div>
            <img src="{{ asset('images/historia/nossa-historia.png') }}" alt="Mulher segurando um ramo da planta de marula" class="fm-historia-body__image" loading="lazy">
        </div>
    </div>
</section>

{{-- Nossa Filosofia (Secção Nosso melhor / 212:542) --}}
<section class="fm-historia-filosofia">
    <div class="fm-container">
        <h2 class="fm-heading-lg text-center mb-5 text-white">Nossa Filosofia</h2>
        <div class="row g-4">
            @php
                $filosofia = [
                    ['title' => 'Missão', 'text' => 'Oferecer cosméticos naturais e de alta qualidade que promovam hidratação, proteção e bem-estar, proporcionando uma rotina de cuidados simples, eficaz e inspirada na riqueza da natureza africana.'],
                    ['title' => 'Visão', 'text' => 'Ser uma marca de referência em cosméticos naturais, reconhecida pela qualidade, inovação e compromisso com o cuidado da pele.'],
                    ['title' => 'Compromisso', 'text' => 'Desenvolvemos cosméticos que combinam ingredientes naturais, qualidade e bem-estar para cuidar da sua pele todos os dias.'],
                ];
            @endphp
            @foreach ($filosofia as $item)
                <div class="col-12 col-md-4">
                    <div class="fm-filosofia-card">
                        <p class="fm-filosofia-card__title">{{ $item['title'] }}</p>
                        <p class="fm-filosofia-card__text mb-0">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Nossos Valores (Secçã 3 / 212:628) --}}
<section class="fm-historia-valores">
    <div class="fm-container">
        <div class="fm-historia-valores__grid">
            <div class="fm-historia-valores__text">
                <h2 class="fm-heading-jomolhari">Nossos Valores</h2>
                @php
                    $valores = [
                        ['emoji' => '🌿', 'title' => 'Ingredientes Naturais', 'text' => 'Selecionamos ingredientes de origem natural para criar fórmulas suaves e eficazes.'],
                        ['emoji' => '✨', 'title' => 'Qualidade', 'text' => 'Cada produto é desenvolvido com rigor para oferecer segurança e excelente desempenho.'],
                        ['emoji' => '🤎', 'title' => 'Respeito à Pele', 'text' => 'Criamos produtos pensados para diferentes tipos de pele, valorizando especialmente as necessidades da pele rica em melanina.'],
                    ];
                @endphp
                <ul class="fm-historia-valores__list">
                    @foreach ($valores as $valor)
                        <li>
                            <p class="fm-historia-valores__item-title mb-1">{{ $valor['emoji'] }} {{ $valor['title'] }}</p>
                            <p class="fm-historia-valores__item-text mb-0">{{ $valor['text'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <img src="{{ asset('images/historia/nossos-valores.jpg') }}" alt="Ingredientes naturais Flor de Marula" class="fm-historia-valores__image" loading="lazy">
        </div>
    </div>
</section>

@endsection
