{{-- Clientes adoram a Flor de Marula — reutilizada na Home e nos Detalhes do Produto (214:280 / Secção Nosso melhor) --}}
<section class="fm-testimonials">
    <div class="fm-container">
        @if (!empty($homeHeadingStyle))
            <h2 class="fm-heading-italiana fm-heading-split text-center mb-2">+300.000 Clientes adoram a <span class="fm-accent-word">Flor de Marula</span></h2>
        @else
            <h2 class="fm-heading-lg text-center mb-2">+300.000 Clientes adoram a Flor de Marula</h2>
        @endif
        <p class="fm-body-lg text-center mb-5" style="font-size: 25px; font-weight: 200;">Veja o que alguns deles têm a dizer sobre seu produto favorito</p>
        @php $testimonialImages = ['testimonial-1.png', 'testimonial-2.png', 'testimonial-3.png']; @endphp
        <div class="row g-4 fm-testimonial-cards{{ !empty($homeHeadingStyle) ? ' fm-testimonial-cards--carousel' : '' }}">
            @foreach ($testimonialImages as $image)
                <div class="col-12 col-md-4 fm-testimonial-cards__item">
                    <div class="fm-testimonial-card">
                        <img src="{{ asset('images/home/' . $image) }}" alt="Cliente satisfeito Flor de Marula" loading="lazy">
                        <p class="fm-testimonial-card__caption mb-0">"Melhor sabonete de cúrcuma{{ "\n" }}do mercado</p>
                    </div>
                </div>
            @endforeach
        </div>
        @if (!empty($homeHeadingStyle))
            <div class="fm-carousel-dots d-flex d-lg-none justify-content-center gap-2 mt-4" data-carousel-dots>
                @foreach ($testimonialImages as $i => $image)
                    <button type="button" class="fm-carousel-dot{{ $i === 0 ? ' is-active' : '' }}" aria-label="Ver depoimento {{ $i + 1 }}" data-carousel-dot></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
