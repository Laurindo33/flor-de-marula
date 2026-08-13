@extends('layouts.app')

@section('title', 'Centro de Ajuda')
@section('meta_description', 'Fale com a equipa Flor de Marula — tire dúvidas, envie sugestões ou peça ajuda com os nossos produtos.')

@section('content')

{{-- Hero "Estamos aqui para si" (Frame 24 / 212:680) --}}
<section class="fm-ajuda-hero">
    <div class="fm-container">
        <div class="fm-ajuda-hero__grid">
            <div class="fm-ajuda-hero__text">
                <h1 class="fm-heading-xl">Centro de ajuda</h1>
                <p class="fm-body-lg">Estamos aqui para si</p>
                <p class="fm-ajuda-hero__paragraph">Tem alguma dúvida, sugestão ou precisa de ajuda com os nossos produtos?{{ "\n" }}A equipa da Flor de Marula está pronta para atendê-lo. Entre em contacto connosco e responderemos o mais breve possível.</p>
            </div>
            <img src="{{ asset('images/ajuda/hero.png') }}" alt="Equipa de atendimento Flor de Marula" class="fm-ajuda-hero__image">
        </div>
    </div>
</section>

{{-- Centro de ajuda: canais de contacto + formulario (Frame 23 / 202:600) --}}
<section class="fm-ajuda-body">
    <div class="fm-container">
        <div class="fm-ajuda-body__grid">
            <div class="fm-ajuda-channels">
                <h2 class="fm-heading-sm">Centro de ajuda</h2>
                <p class="fm-body-md fm-ajuda-channels__lead">Queremos tornar a sua experiência a melhor possível.</p>
                <p class="fm-ajuda-channels__text">Entre em contacto através dos nossos canais de atendimento.{{ "\n" }}Será um prazer ajudá-lo!</p>

                @php
                    $canais = [
                        ['icon' => 'icon-phone.png', 'badge' => '#ffd595', 'title' => 'Nosso Contacto', 'value' => '+244 945960249', 'href' => 'tel:+244945960249'],
                        ['icon' => 'icon-email.png', 'badge' => '#eb95ff', 'title' => 'Nosso E-mail', 'value' => 'flordemarula@gmail.com', 'href' => 'mailto:flordemarula@gmail.com'],
                        ['icon' => 'icon-marker.png', 'badge' => '#95c1ff', 'title' => 'Visite-nos', 'value' => 'Luanda, Talatona, Rua dos Mirantes', 'href' => null],
                        ['icon' => 'icon-instagram.png', 'badge' => '#95ff9a', 'title' => 'Instagran', 'value' => '@flordemarula', 'href' => 'https://instagram.com/flordemarula'],
                    ];
                @endphp
                <div class="fm-ajuda-channels__grid">
                    @foreach ($canais as $canal)
                        <div class="fm-channel-card">
                            <span class="fm-channel-card__badge" style="background-color: {{ $canal['badge'] }};">
                                <img src="{{ asset('images/ajuda/' . $canal['icon']) }}" alt="">
                            </span>
                            <p class="fm-channel-card__title mb-0">{{ $canal['title'] }}</p>
                            @if ($canal['href'])
                                <a href="{{ $canal['href'] }}" class="fm-channel-card__value">{{ $canal['value'] }}</a>
                            @else
                                <p class="fm-channel-card__value mb-0">{{ $canal['value'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="fm-ajuda-form-col">
                <h2 class="fm-heading-sm">Fale Connosco</h2>
                <p class="fm-body-md fm-ajuda-form-col__lead">Envie a sua mensagem</p>
                <p class="fm-ajuda-form-col__text">Preencha o formulário e conte-nos como podemos ajudar. A nossa equipa analisará a sua solicitação e responderá com rapidez e atenção.</p>

                <form action="{{ route('support-message.store') }}" method="POST" class="fm-ajuda-form">
                    @csrf
                    <div class="fm-ajuda-form__field">
                        <label for="support-name">Nome Completo</label>
                        <input type="text" name="name" id="support-name" value="{{ old('name') }}" placeholder="Ex.: Laurindo Henriques Moisés" required>
                        @error('name') <p class="fm-ajuda-form__error">{{ $message }}</p> @enderror
                    </div>
                    <div class="fm-ajuda-form__field">
                        <label for="support-email">E-mail</label>
                        <input type="email" name="email" id="support-email" value="{{ old('email') }}" placeholder="Ex.: laurindomoises@gmail.com" required>
                        @error('email') <p class="fm-ajuda-form__error">{{ $message }}</p> @enderror
                    </div>
                    <div class="fm-ajuda-form__field">
                        <label for="support-message">Sua questão</label>
                        <textarea name="message" id="support-message" rows="4" placeholder="Sua Mensagem" required>{{ old('message') }}</textarea>
                        @error('message') <p class="fm-ajuda-form__error">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="fm-ajuda-form__submit">Enviar</button>
                    @if (session('support_message_success'))
                        <p class="fm-ajuda-form__success">{{ session('support_message_success') }}</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Mapa, Endereco e Direcoes (Secção Nosso melhor / Banner) --}}
<section class="fm-ajuda-map">
    <div class="fm-container">
        <img src="{{ asset('images/ajuda/map.png') }}" alt="Mapa — Talatona, Mirantes, Luanda, Angola" class="fm-ajuda-map__image" loading="lazy">
        <div class="fm-ajuda-map__row">
            <span class="fm-ajuda-map__address">Talatona, mirantes, Luanda, Angola</span>
            <a href="https://maps.google.com/?q=Talatona,Luanda,Angola" target="_blank" rel="noopener" class="fm-btn fm-btn-primary">Direções</a>
        </div>
    </div>
</section>

@endsection
