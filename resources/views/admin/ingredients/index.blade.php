@extends('admin.layout')

@section('title', 'Ingredientes')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Ingredientes</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th></th><th>Nome</th><th>Descrição</th><th>Produtos</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($ingredients as $ingredient)
                            <tr>
                                <td>
                                    <img src="{{ asset($ingredient->image_path) }}" alt="" style="width:48px; height:48px; object-fit:cover; border-radius:6px;">
                                </td>
                                <td>
                                    <form action="{{ route('admin.ingredients.update', $ingredient) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2" style="min-width:220px;">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $ingredient->name }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px;" required>
                                        <textarea name="description" rows="2" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; font-size:13px;">{{ $ingredient->description }}</textarea>
                                        <input type="file" name="image_path" accept="image/*" style="font-size:12px;">
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Guardar</button>
                                    </form>
                                </td>
                                <td class="text-muted" style="font-size:12px; max-width:220px;">{{ Str::limit($ingredient->description, 80) }}</td>
                                <td>{{ $ingredient->products_count }}</td>
                                <td>
                                    <form action="{{ route('admin.ingredients.destroy', $ingredient) }}" method="POST" data-confirm="Eliminar este ingrediente? Ele será removido de todos os produtos.">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="fm-admin-empty">Nenhum ingrediente criado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Novo Ingrediente</p>
            <form action="{{ route('admin.ingredients.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="fm-admin-field">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="fm-admin-field">
                    <label for="description">Descrição</label>
                    <textarea name="description" id="description" rows="3"></textarea>
                </div>
                <div class="fm-admin-field">
                    <label for="image_path">Imagem</label>
                    <input type="file" name="image_path" id="image_path" accept="image/*" required>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar Ingrediente</button>
            </form>
        </div>
        <p class="fm-admin-empty" style="text-align:left; padding:0;">
            Depois de criado, associe o ingrediente a produtos na página de edição de cada produto (secção "Ingredientes" em "Feito com ingredientes 100% naturais").
        </p>
    </div>
</div>

@endsection
