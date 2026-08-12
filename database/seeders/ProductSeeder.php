<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Produtos extraidos do frame "Home Page" do Figma (secoes Produtos em Alta
     * e Nosso Melhor Produto). As duas seccoes do design mostram precos
     * diferentes para os mesmos nomes de produto (dados de placeholder do
     * Figma) — aqui usa-se o preco da seccao "Nosso Melhor Produto" como
     * preco canonico, com compare_price a refletir o desconto de 10% exibido
     * nos cards de "Produtos em Alta".
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Sérum Facial',
                'slug' => 'serum-facial',
                'sku' => 'FM-SER-001',
                'description' => 'Óleo semente da Árvore de Marula, Vitamina E, Óleo de rosa e pétalas. Dá um brilho de pele de vidro.',
                'price' => 14500,
                'compare_price' => 16111,
                'image_path' => 'images/home/best-serum-facial.png',
                'stock' => 60,
                'is_featured' => true,
                'is_best_seller' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Creme Hidratante',
                'slug' => 'creme-hidratante',
                'sku' => 'FM-CRE-001',
                'description' => 'Hidratação profunda com ingredientes de origem natural, formulado para pele com melanina.',
                'price' => 20000,
                'compare_price' => null,
                'image_path' => 'images/home/best-creme-hidratante.png',
                'stock' => 45,
                'is_featured' => false,
                'is_best_seller' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Protetor Solar',
                'slug' => 'protetor-solar',
                'sku' => 'FM-PRO-001',
                'description' => 'Proteção diária com ingredientes naturais, sem resíduos brancos.',
                'price' => 20000,
                'compare_price' => 22222,
                'image_path' => 'images/home/best-protetor-solar.png',
                'stock' => 50,
                'is_featured' => true,
                'is_best_seller' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
