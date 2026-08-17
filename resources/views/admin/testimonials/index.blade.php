@extends('admin.layout')

@section('title', 'Depoimentos')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Depoimentos ({{ $testimonials->count() }})</p>

            @if ($testimonials->isNotEmpty())
                <div class="fm-admin-gallery">
                    @foreach ($testimonials as $testimonial)
                        <div class="fm-admin-gallery__item">
                            <img src="{{ asset($testimonial->image_path) }}" alt="Depoimento de cliente">
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" data-confirm="Remover este depoimento?">
                                @csrf @method('DELETE')
                                <button type="submit" class="fm-admin-gallery__remove">Remover</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="fm-admin-empty">Nenhum depoimento adicionado.</p>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Adicionar Depoimentos</p>
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="fm-admin-field">
                    <label for="screenshots">Capturas de ecrã</label>
                    <input type="file" name="screenshots[]" id="screenshots" accept="image/*" multiple required>
                    <small>Pode selecionar várias imagens de uma vez. Aparecem na home pela ordem em que forem adicionadas.</small>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Adicionar</button>
            </form>
        </div>
    </div>
</div>

@endsection
