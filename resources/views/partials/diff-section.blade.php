{{-- A Diferenca dos Nossos Produtos — reutilizada na Home e nos Detalhes do Produto (214:121 / Secção Nosso melhor) --}}
@php
    $diffs = [
        ['category' => 'Hiperpigmentação', 'customer' => 'Ana Paula M.', 'image' => 'diff-hiperpigmentacao.png', 'quote' => 'Comecei a usar a linha Flor de Marula há algumas semanas e senti minha pele muito mais hidratada e macia. O sérum tem uma textura leve e deixa um toque agradável durante o dia.'],
        ['category' => 'Poros Obstruídos', 'customer' => 'Carla S.', 'image' => 'diff-poros-obstruidos.png', 'quote' => 'O que mais gostei foi a sensação de cuidado natural. O creme hidratante absorve rapidamente e não deixa a pele oleosa. Já virou parte da minha rotina diária.'],
        ['category' => 'Manchas', 'customer' => 'Patrícia L.', 'image' => 'diff-manchas.png', 'quote' => 'A combinação do gel de limpeza com o tônico fez toda a diferença na minha rotina. Minha pele ficou com aspecto mais fresco e bem cuidada.'],
        ['category' => 'Acne + Oleosidade', 'customer' => 'Juliana R.', 'image' => 'diff-acne-oleosidade.png', 'quote' => 'O protetor solar é excelente! Espalha facilmente, não deixa resíduos brancos e a pele fica confortável mesmo depois de várias horas.'],
    ];
@endphp
<section class="fm-diff-section">
    <div class="fm-container">
        <h2 class="fm-heading-lg text-center mb-5">A Diferença dos Nossos Produtos</h2>
        <div class="row g-4">
            @foreach ($diffs as $diff)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="fm-diff-card">
                        <div class="fm-diff-card__media">
                            <img src="{{ asset('images/home/' . $diff['image']) }}" alt="Antes e depois — {{ $diff['category'] }}" loading="lazy">
                            <span class="fm-pill fm-pill--dark fm-diff-card__before">Antes</span>
                            <span class="fm-pill fm-pill--light fm-diff-card__after">Depois</span>
                            <span class="fm-pill fm-pill--light fm-diff-card__category">{{ $diff['category'] }}</span>
                        </div>
                        <p class="fm-diff-card__name">{{ $diff['customer'] }}</p>
                        <p class="fm-quote">&ldquo;{{ $diff['quote'] }}&rdquo;</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
