<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    public function index()
    {
        return view('admin.ingredients.index', [
            'ingredients' => Ingredient::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->safely(function () use ($request) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'image_path' => ['required', 'image', 'max:5120'],
            ]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['image_path'] = 'storage/' . $request->file('image_path')->store('ingredients', 'public');

            Ingredient::create($validated);

            return back()->with('admin_success', 'Ingrediente criado com sucesso.');
        });
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        return $this->safely(function () use ($request, $ingredient) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'image_path' => ['nullable', 'image', 'max:5120'],
            ]);

            if ($request->hasFile('image_path')) {
                $validated['image_path'] = 'storage/' . $request->file('image_path')->store('ingredients', 'public');
            }

            $ingredient->update($validated);

            return back()->with('admin_success', 'Ingrediente atualizado.');
        });
    }

    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        $ingredient->delete();

        return back()->with('admin_success', 'Ingrediente eliminado.');
    }
}
