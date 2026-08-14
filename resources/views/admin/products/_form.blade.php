@php
    $product = $product ?? null;
@endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Informação Geral</p>

            <div class="fm-admin-field">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product?->name) }}" required>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="fm-admin-field">
                        <label for="slug">Slug (opcional — gerado do nome se vazio)</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $product?->slug) }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="fm-admin-field">
                        <label for="sku">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product?->sku) }}" required>
                    </div>
                </div>
            </div>

            <div class="fm-admin-field">
                <label for="description">Descrição</label>
                <textarea name="description" id="description" rows="3">{{ old('description', $product?->description) }}</textarea>
            </div>

            <div class="fm-admin-field">
                <label for="benefits_text">Benefícios (um por linha)</label>
                <textarea name="benefits_text" id="benefits_text" rows="3">{{ old('benefits_text', $product ? implode("\n", $product->benefits ?? []) : '') }}</textarea>
            </div>

            <div class="fm-admin-field">
                <label for="how_to_use">Como Usar</label>
                <textarea name="how_to_use" id="how_to_use" rows="3">{{ old('how_to_use', $product?->how_to_use) }}</textarea>
            </div>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Categorias</p>
            <div class="d-flex flex-wrap gap-3">
                @foreach ($categories as $category)
                    <label class="fm-admin-checkbox">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', $productCategoryIds ?? [])) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Preço e Stock</p>
            <div class="fm-admin-field">
                <label for="price">Preço (kz)</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product?->price) }}" required min="0">
            </div>
            <div class="fm-admin-field">
                <label for="compare_price">Preço Promocional Anterior (kz, opcional)</label>
                <input type="number" name="compare_price" id="compare_price" value="{{ old('compare_price', $product?->compare_price) }}" min="0">
            </div>
            <div class="fm-admin-field">
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product?->stock ?? 0) }}" required min="0">
            </div>
            <div class="fm-admin-field">
                <label for="stock_minimo">Stock Mínimo</label>
                <input type="number" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo', $product?->stock_minimo ?? 5) }}" required min="0">
            </div>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Imagem Principal</p>
            @if ($product?->image_path)
                <img src="{{ asset($product->image_path) }}" alt="" style="width:100%; border-radius:8px; margin-bottom:12px;">
            @endif
            <div class="fm-admin-field">
                <input type="file" name="image_path" accept="image/*">
                <small>Deixe em branco para manter a imagem atual.</small>
            </div>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Galeria de Imagens</p>

            @if ($product?->images?->isNotEmpty())
                <div class="fm-admin-gallery">
                    @foreach ($product->images as $image)
                        <div class="fm-admin-gallery__item">
                            <img src="{{ asset($image->image_path) }}" alt="">
                            <button
                                type="button"
                                class="fm-admin-gallery__remove"
                                data-admin-delete-image
                                data-url="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                                data-confirm="Remover esta imagem da galeria?"
                            >Remover</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="fm-admin-field">
                <label for="gallery_images">Adicionar imagens</label>
                <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple>
                <small>Aparecem na página do produto como miniaturas, na ordem em que forem adicionadas.</small>
            </div>
        </div>

        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Definições</p>
            <label class="fm-admin-checkbox">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product?->is_featured) ? 'checked' : '' }}>
                Produto em destaque (Produtos em Alta)
            </label>
            <label class="fm-admin-checkbox">
                <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product?->is_best_seller) ? 'checked' : '' }}>
                Nosso Melhor Produto
            </label>
            <label class="fm-admin-checkbox">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}>
                Ativo (visível na loja)
            </label>

            <div class="fm-admin-field mt-3">
                <label for="routine_product_id">Complete Sua Rotina (produto sugerido)</label>
                <select name="routine_product_id" id="routine_product_id">
                    <option value="">Nenhum</option>
                    @foreach ($routineOptions as $option)
                        <option value="{{ $option->id }}" {{ old('routine_product_id', $product?->routine_product_id) == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="fm-admin-field">
                <label for="sort_order">Ordem de Exibição</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $product?->sort_order ?? 0) }}">
            </div>
        </div>
    </div>
</div>

<button type="submit" class="fm-admin-btn fm-admin-btn--primary">Guardar Produto</button>
<a href="{{ route('admin.products.index') }}" class="fm-admin-btn fm-admin-btn--outline">Cancelar</a>
