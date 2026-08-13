@extends('admin.layout')

@section('title', 'FAQ')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Perguntas Frequentes</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Pergunta</th><th>Produto</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td>{{ $faq->question }}</td>
                                <td>{{ $faq->product->name ?? 'Geral' }}</td>
                                <td>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" data-confirm="Eliminar esta pergunta?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="fm-admin-empty">Nenhuma FAQ criada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Nova Pergunta</p>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="product_id">Produto (opcional — geral se vazio)</label>
                    <select name="product_id" id="product_id">
                        <option value="">Geral (Centro de Ajuda)</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fm-admin-field">
                    <label for="question">Pergunta</label>
                    <input type="text" name="question" id="question" required>
                </div>
                <div class="fm-admin-field">
                    <label for="answer">Resposta</label>
                    <textarea name="answer" id="answer" rows="4" required></textarea>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar</button>
            </form>
        </div>
    </div>
</div>

@endsection
