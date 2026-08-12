<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Catalogo extraido dos frames "Home Page" e "Loja" do Figma. Onde o
     * design mostra precos/imagens diferentes para o mesmo produto em
     * paginas distintas (dados de placeholder do Figma — ex: Protetor Solar
     * a 20.000kz na Home e 25.000kz na Loja), usa-se um preco/imagem
     * canonico unico por produto, como exige um catalogo real.
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
                'image_path' => 'images/products/serum-facial.png',
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
                'image_path' => 'images/products/creme-hidratante.png',
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
                'image_path' => 'images/products/protetor-solar.png',
                'stock' => 50,
                'is_featured' => true,
                'is_best_seller' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Gel de Limpeza',
                'slug' => 'gel-de-limpeza',
                'sku' => 'FM-GEL-001',
                'description' => 'Limpeza suave que remove impurezas sem ressecar a pele.',
                'price' => 23000,
                'compare_price' => null,
                'image_path' => 'images/products/gel-de-limpeza.png',
                'stock' => 40,
                'is_featured' => false,
                'is_best_seller' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Contorno de Olhos',
                'slug' => 'contorno-de-olhos',
                'sku' => 'FM-CON-001',
                'description' => 'Cuidado especifico para a area dos olhos, reduz sinais de cansaço.',
                'price' => 18000,
                'compare_price' => null,
                'image_path' => 'images/products/contorno-de-olhos.png',
                'stock' => 35,
                'is_featured' => false,
                'is_best_seller' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Tónico Facial',
                'slug' => 'tonico-facial',
                'sku' => 'FM-TON-001',
                'description' => 'Equilibra o pH da pele e prepara para os proximos passos da rotina.',
                'price' => 24000,
                'compare_price' => null,
                'image_path' => 'images/products/tonico-facial.png',
                'stock' => 38,
                'is_featured' => false,
                'is_best_seller' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Sabonete Facial',
                'slug' => 'sabonete-facial',
                'sku' => 'FM-SAB-001',
                'description' => 'Sabonete facial suave com ingredientes de origem natural.',
                'price' => 20000,
                'compare_price' => null,
                'image_path' => 'images/products/sabonete-facial.png',
                'stock' => 42,
                'is_featured' => false,
                'is_best_seller' => false,
                'sort_order' => 7,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
