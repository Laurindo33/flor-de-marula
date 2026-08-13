<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Limpeza', 'slug' => 'limpeza', 'sort_order' => 1],
            ['name' => 'Hidratação', 'slug' => 'hidratacao', 'sort_order' => 2],
            ['name' => 'Tratamento', 'slug' => 'tratamento', 'sort_order' => 3],
            ['name' => 'Proteção Solar', 'slug' => 'protecao-solar', 'sort_order' => 4],
            ['name' => 'Olhos', 'slug' => 'olhos', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $assignments = [
            'serum-facial' => ['tratamento', 'hidratacao'],
            'creme-hidratante' => ['hidratacao'],
            'protetor-solar' => ['protecao-solar'],
            'gel-de-limpeza' => ['limpeza'],
            'contorno-de-olhos' => ['olhos', 'tratamento'],
            'tonico-facial' => ['limpeza'],
            'sabonete-facial' => ['limpeza'],
        ];

        foreach ($assignments as $productSlug => $categorySlugs) {
            $product = Product::where('slug', $productSlug)->first();
            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id');
            $product->categories()->sync($categoryIds);
        }
    }
}
