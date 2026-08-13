@extends('admin.layout')

@section('title', 'Categorias')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Categorias</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Nome</th><th>Produtos</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="d-flex gap-2">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px;">
                                        <input type="hidden" name="sort_order" value="{{ $category->sort_order }}">
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Guardar</button>
                                    </form>
                                </td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Eliminar esta categoria?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="fm-admin-empty">Nenhuma categoria criada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Nova Categoria</p>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="fm-admin-field">
                    <label for="sort_order">Ordem</label>
                    <input type="number" name="sort_order" id="sort_order" value="0">
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar Categoria</button>
            </form>
        </div>
    </div>
</div>

@endsection
