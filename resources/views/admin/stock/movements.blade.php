@extends('admin.layout')

@section('title', 'Stock — ' . $product->name)

@section('content')

<a href="{{ route('admin.stock.index') }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm mb-3">← Voltar ao Stock</a>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">{{ $product->name }} — Stock atual: {{ $product->stock }}</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Data</th><th>Tipo</th><th>Quantidade</th><th>Nota</th></tr></thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="fm-badge fm-badge--neutral">{{ ucfirst($movement->type) }}</span></td>
                                <td>{{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}</td>
                                <td>{{ $movement->note }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="fm-admin-empty">Nenhum movimento registado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Registar Movimento</p>
            <form action="{{ route('admin.stock.store', $product) }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="type">Tipo</label>
                    <select name="type" id="type" required>
                        <option value="entrada">Entrada</option>
                        <option value="venda">Venda</option>
                        <option value="ajuste">Ajuste</option>
                        <option value="cancelamento">Cancelamento</option>
                        <option value="devolucao">Devolução</option>
                    </select>
                </div>
                <div class="fm-admin-field">
                    <label for="quantity">Quantidade (negativo para saída)</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>
                <div class="fm-admin-field">
                    <label for="note">Nota (opcional)</label>
                    <input type="text" name="note" id="note">
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Registar</button>
            </form>
        </div>
    </div>
</div>

@endsection
