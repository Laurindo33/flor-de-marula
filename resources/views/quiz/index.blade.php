@extends('layouts.app')

@section('title', 'Quiz da Pele')
@section('meta_description', 'Descubra a sua rotina Flor de Marula ideal em 3 perguntas rápidas.')

@section('content')

<section class="fm-quiz-hero">
    <div class="fm-container text-center">
        <h1 class="fm-heading-italiana mb-2">Descubra a sua rotina Flor de Marula</h1>
        <p class="fm-body-lg">Responda a 3 perguntas rápidas e receba uma recomendação personalizada.</p>
    </div>
</section>

<section class="fm-quiz">
    <div class="fm-container">
        <form action="{{ route('quiz.result') }}" method="POST" class="fm-quiz-form" id="fmQuizForm">
            @csrf

            <div class="fm-quiz-progress">
                <span class="fm-quiz-progress__dot active" data-fm-quiz-dot="1"></span>
                <span class="fm-quiz-progress__dot" data-fm-quiz-dot="2"></span>
                <span class="fm-quiz-progress__dot" data-fm-quiz-dot="3"></span>
            </div>

            <div class="fm-quiz-step" data-fm-quiz-step="1">
                <h2 class="fm-quiz-step__title">Qual é o seu tipo de pele?</h2>
                <div class="fm-quiz-options">
                    @foreach ($skinTypes as $option)
                        <label class="fm-quiz-option">
                            <input type="radio" name="skin_type" value="{{ $option }}" required>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="fm-quiz-step" data-fm-quiz-step="2" hidden>
                <h2 class="fm-quiz-step__title">Qual é a sua principal preocupação?</h2>
                <div class="fm-quiz-options">
                    @foreach ($concerns as $option)
                        <label class="fm-quiz-option">
                            <input type="radio" name="concern" value="{{ $option }}" required>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="fm-quiz-step" data-fm-quiz-step="3" hidden>
                <h2 class="fm-quiz-step__title">Qual é o seu objetivo?</h2>
                <div class="fm-quiz-options">
                    @foreach ($goals as $option)
                        <label class="fm-quiz-option">
                            <input type="radio" name="goal" value="{{ $option }}" required>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="fm-btn fm-btn-primary fm-quiz-submit">Ver a Minha Rotina</button>
            </div>
        </form>
    </div>
</section>

@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
    const steps = document.querySelectorAll('[data-fm-quiz-step]');
    const dots = document.querySelectorAll('[data-fm-quiz-dot]');

    steps.forEach((step, index) => {
        step.querySelectorAll('input[type="radio"]').forEach((input) => {
            input.addEventListener('change', () => {
                if (index < steps.length - 1) {
                    steps[index].hidden = true;
                    steps[index + 1].hidden = false;
                    dots.forEach((d) => d.classList.remove('active'));
                    dots[index + 1].classList.add('active');
                }
            });
        });
    });
</script>
@endpush
