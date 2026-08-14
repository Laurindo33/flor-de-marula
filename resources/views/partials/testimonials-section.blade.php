{{-- Clientes adoram a Flor de Marula — reutilizada na Home e nos Detalhes do Produto (214:280 / Secção Nosso melhor) --}}
<section class="fm-testimonials {{ $sectionClass ?? '' }}">
    <div class="fm-container">
        @if (!empty($homeHeadingStyle))
            <h2 class="fm-heading-italiana fm-heading-split text-center mb-2">+300.000 Clientes adoram a <span class="fm-accent-word">Flor de Marula</span></h2>
        @else
            <h2 class="fm-heading-lg text-center mb-2">+300.000 Clientes adoram a Flor de Marula</h2>
        @endif
        <p class="fm-body-lg fm-testimonials__subtitle text-center mb-5">Veja o que alguns deles têm a dizer sobre seu produto favorito</p>
        @php
            $testimonialVideos = [
                ['file' => 'testimonial-1.mp4', 'caption' => "Melhor sérum facial\ndo mercado"],
                ['file' => 'testimonial-2.mp4', 'caption' => "Minha pele nunca\nesteve tão radiante"],
                ['file' => 'testimonial-3.mp4', 'caption' => "Resultado visível\nem poucos dias"],
            ];
        @endphp
        <div class="row g-4 fm-testimonial-cards{{ !empty($homeHeadingStyle) ? ' fm-testimonial-cards--carousel' : '' }}">
            @foreach ($testimonialVideos as $testimonial)
                <div class="col-12 col-md-4 fm-testimonial-cards__item">
                    <div class="fm-testimonial-card">
                        <video src="{{ asset('videos/home/' . $testimonial['file']) }}" autoplay muted loop playsinline controls preload="metadata" aria-label="Depoimento em vídeo de cliente Flor de Marula"></video>
                        <p class="fm-testimonial-card__caption mb-0">"{{ $testimonial['caption'] }}"</p>
                    </div>
                </div>
            @endforeach
        </div>
        @if (!empty($homeHeadingStyle))
            <div class="fm-carousel-dots d-flex d-lg-none justify-content-center gap-2 mt-4" data-carousel-dots>
                @foreach ($testimonialVideos as $i => $testimonial)
                    <button type="button" class="fm-carousel-dot{{ $i === 0 ? ' is-active' : '' }}" aria-label="Ver depoimento {{ $i + 1 }}" data-carousel-dot></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
