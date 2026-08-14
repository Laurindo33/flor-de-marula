<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->string('busca')->toString(), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'ingredients' => Ingredient::orderBy('name')->get(),
            'routineOptions' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $this->validateProduct($request);

            $validated['slug'] = $request->input('slug') ?: Str::slug($validated['name']);
            $validated['benefits'] = $this->parseBenefits($request->input('benefits_text'));

            if ($request->hasFile('image_path')) {
                $validated['image_path'] = 'storage/' . $request->file('image_path')->store('products', 'public');
            }

            $product = Product::create($validated);
            $product->categories()->sync($request->input('categories', []));
            $this->syncIngredients($request, $product);
            $this->syncGalleryImages($request, $product);

            return redirect()->route('admin.products.index')->with('admin_success', 'Produto criado com sucesso.');
        });
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'productCategoryIds' => $product->categories()->pluck('categories.id')->toArray(),
            'ingredients' => Ingredient::orderBy('name')->get(),
            'productIngredientIds' => $product->ingredients()->pluck('ingredients.id')->toArray(),
            'routineOptions' => Product::where('id', '!=', $product->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        return $this->safely(function () use ($request, $product) {
            $validated = $this->validateProduct($request, $product);

            $validated['slug'] = $request->input('slug') ?: Str::slug($validated['name']);
            $validated['benefits'] = $this->parseBenefits($request->input('benefits_text'));

            if ($request->hasFile('image_path')) {
                $validated['image_path'] = 'storage/' . $request->file('image_path')->store('products', 'public');
            }

            $product->update($validated);
            $product->categories()->sync($request->input('categories', []));
            $this->syncIngredients($request, $product);
            $this->syncGalleryImages($request, $product);

            return redirect()->route('admin.products.index')->with('admin_success', 'Produto atualizado com sucesso.');
        });
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('admin_success', 'Produto eliminado.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_if($image->product_id !== $product->id, 404);

        Storage::disk('public')->delete(Str::after($image->image_path, 'storage/'));
        $image->delete();

        return back()->with('admin_success', 'Imagem removida.');
    }

    public function toggleActive(Product $product): RedirectResponse
    {
        return $this->safely(function () use ($product) {
            $product->update(['is_active' => ! $product->is_active]);

            return back()->with('admin_success', $product->is_active ? 'Produto ativado.' : 'Produto desativado.');
        });
    }

    public function duplicate(Product $product): RedirectResponse
    {
        $copy = $product->replicate();
        $copy->name = $product->name . ' (Cópia)';
        $copy->slug = Str::slug($copy->name) . '-' . Str::random(4);
        $copy->sku = $product->sku . '-COPY-' . Str::upper(Str::random(4));
        $copy->save();
        $copy->categories()->sync($product->categories()->pluck('categories.id'));

        return redirect()->route('admin.products.edit', $copy)->with('admin_success', 'Produto duplicado. Reveja os dados antes de guardar.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $productId = $product?->id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $productId],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,' . $productId],
            'description' => ['nullable', 'string'],
            'ingredients_list' => ['nullable', 'string'],
            'how_to_use' => ['nullable', 'string'],
            'expert_review' => ['nullable', 'string'],
            'shipping_returns' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'compare_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'image_path' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*' => ['integer', 'exists:ingredients,id'],
            'routine_product_id' => ['nullable', 'exists:products,id'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function syncGalleryImages(Request $request, Product $product): void
    {
        $files = $request->file('gallery_images', []);
        if (! $files) {
            return;
        }

        $nextOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($files as $file) {
            $product->images()->create([
                'image_path' => 'storage/' . $file->store('products', 'public'),
                'sort_order' => $nextOrder++,
            ]);
        }
    }

    private function syncIngredients(Request $request, Product $product): void
    {
        $ingredientIds = $request->input('ingredients', []);

        $product->ingredients()->sync(
            collect($ingredientIds)->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i]])->all()
        );
    }

    private function parseBenefits(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return collect(explode("\n", $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
