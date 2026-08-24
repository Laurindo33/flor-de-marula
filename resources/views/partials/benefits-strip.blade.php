{{-- Faixa de beneficios — reutilizada na Home e nos Detalhes do Produto (Secçã 2 / 213:1689) --}}
<section class="fm-benefits-strip {{ $sectionClass ?? '' }}">
    <div class="fm-container">
        <div class="fm-benefits-card">
            <div class="fm-benefits-card__grid">
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
                <img src="{{ asset('images/home/benefits/badge-heart-africa.png') }}" alt="" class="fm-benefits-card__africa-icon">
                <span class="fm-benefits-card__africa-text">Orgulhosamente<strong>Africano</strong></span>
                <span class="fm-benefits-card__africa-ornament">✦</span>
            </div>
        </div>
    </div>
</section>
