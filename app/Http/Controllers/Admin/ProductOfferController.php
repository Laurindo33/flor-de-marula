<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductOfferController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        return $this->safely(function () use ($request, $product) {
            $validated = $request->validate([
                'label' => ['required', 'string', 'max:50'],
                'quantity' => ['required', 'integer', 'min:1'],
                'price' => ['required', 'integer', 'min:0'],
                'image_path' => ['nullable', 'image', 'max:5120'],
            ]);

            if ($request->hasFile('image_path')) {
                $validated['image_path'] = 'storage/' . $request->file('image_path')->store('offers', 'public');
            }

            $nextOrder = (int) $product->offers()->max('sort_order') + 1;

            $product->offers()->create($validated + ['sort_order' => $nextOrder]);

            return back()->with('admin_success', 'Oferta criada com sucesso.');
        });
    }

    public function update(Request $request, Product $product, ProductOffer $offer): RedirectResponse
    {
        abort_if($offer->product_id !== $product->id, 404);

        return $this->safely(function () use ($request, $offer) {
            $validated = $request->validate([
                'image_path' => ['required', 'image', 'max:5120'],
            ]);

            $validated['image_path'] = 'storage/' . $request->file('image_path')->store('offers', 'public');

            $offer->update($validated);

            return back()->with('admin_success', 'Imagem da oferta atualizada.');
        });
    }

    public function destroy(Product $product, ProductOffer $offer): RedirectResponse
    {
        abort_if($offer->product_id !== $product->id, 404);

        $offer->delete();

        return back()->with('admin_success', 'Oferta removida.');
    }

    public function move(Request $request, Product $product, ProductOffer $offer): RedirectResponse
    {
        abort_if($offer->product_id !== $product->id, 404);

        $direction = $request->validate([
            'direction' => ['required', 'in:up,down'],
        ])['direction'];

        $offers = $product->offers()->orderBy('sort_order')->get();
        $index = $offers->search(fn ($o) => $o->id === $offer->id);
        $swapIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapIndex < 0 || $swapIndex >= $offers->count()) {
            return back();
        }

        $other = $offers[$swapIndex];
        [$offerOrder, $otherOrder] = [$offer->sort_order, $other->sort_order];
        $offer->update(['sort_order' => $otherOrder]);
        $other->update(['sort_order' => $offerOrder]);

        return back()->with('admin_success', 'Ordem da oferta atualizada.');
    }
}
