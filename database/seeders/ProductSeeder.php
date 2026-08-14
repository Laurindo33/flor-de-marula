<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOffer;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Catalogo extraido dos frames "Home Page", "Loja" e "Detalhes" do
     * Figma. Onde o design mostra precos/imagens diferentes para o mesmo
     * produto em paginas distintas (dados de placeholder do Figma — ex:
     * Protetor Solar a 20.000kz na Home e 25.000kz na Loja, ou Sérum
     * Facial a 14.500kz na Home/Loja e 19.500kz nos "Detalhes"), usa-se um
     * preco/imagem canonico unico por produto, como exige um catalogo real.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Sérum Facial',
                'slug' => 'serum-facial',
                'sku' => 'FM-SER-001',
                'description' => 'Óleo semente da Árvore de Marula, Vitamina E, Óleo de rosa e pétalas. Dá um brilho de pele de vidro.',
                'benefits' => ['Dá um brilho de pele de vidro', 'Hidrata e cura pele seca', 'Atenua manchas escuras'],
                'ingredients_list' => 'Óleo de semente de Vitis vinifera (uva), óleo de caroço de argânia spinosa (argan), óleo de fruta de rosa canina (rosa mosqueta), óleo de fruta de hippophae rhamnoides (espinheiro-do-mar), triglicerídeos caprílicos/cápricos, óleo de caroço de avena sativa (aveia), rosa centifolia, tocoferol (vitamina E), óleo de pelargonium graveolens (gerânio), óleo de flor de rosa damascena (rosa).',
                'how_to_use' => 'Aplique 3 a 4 gotas no rosto e pescoço limpos, de manhã e à noite, antes do hidratante. Massaje suavemente com movimentos circulares até completa absorção.',
                'expert_review' => '"Um sérum leve mas com ativos consistentes — o óleo de marula e a vitamina E ajudam a reforçar a barreira da pele sem deixar sensação oleosa." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Hidratação profunda por 24 horas', 'Reforça a barreira natural da pele', 'Não deixa a pele oleosa'],
                'ingredients_list' => 'Manteiga de karité, óleo de marula, glicerina vegetal, esqualano, tocoferol (vitamina E), extrato de aveia.',
                'how_to_use' => 'Aplique uma quantidade generosa no rosto limpo, de manhã e à noite, com movimentos ascendentes até completa absorção.',
                'expert_review' => '"Uma fórmula rica mas de rápida absorção — ideal para manter a pele confortável durante todo o dia." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Proteção FPS 30 de largo espectro', 'Sem resíduos brancos', 'Textura leve, não oleosa'],
                'ingredients_list' => 'Óleo de marula, óxido de zinco não-nano, manteiga de karité, glicerina vegetal, extrato de chá verde.',
                'how_to_use' => 'Aplique generosamente no rosto e pescoço como último passo da rotina matinal. Reaplique a cada 3 horas em exposição solar direta.',
                'expert_review' => '"Uma proteção diária sem o efeito esbranquiçado comum em protetores minerais — adequado para todos os tons de pele." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Remove impurezas e excesso de oleosidade', 'Não resseca a pele', 'pH equilibrado'],
                'ingredients_list' => 'Água, tensioativos suaves derivados de coco, óleo de marula, extrato de aloé vera, pantenol.',
                'how_to_use' => 'Aplique sobre o rosto húmido, massaje suavemente e enxague com água morna. Use de manhã e à noite.',
                'expert_review' => '"Uma limpeza suave o suficiente para uso diário, sem comprometer a barreira cutânea." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Reduz olheiras e inchaço', 'Hidrata a pele fina da área dos olhos', 'Textura leve, absorção rápida'],
                'ingredients_list' => 'Óleo de marula, cafeína, ácido hialurónico, tocoferol (vitamina E), extrato de pepino.',
                'how_to_use' => 'Aplique uma pequena quantidade com o dedo anelar ao redor dos olhos, de manhã e à noite, com toques leves.',
                'expert_review' => '"Um cuidado direcionado que ajuda a disfarçar o cansaço visível sem irritar a pele sensível dos olhos." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Equilibra o pH da pele', 'Remove resíduos após a limpeza', 'Prepara a pele para os ativos seguintes'],
                'ingredients_list' => 'Água floral de rosas, óleo de marula, extrato de hamamélis, ácido hialurónico, pantenol.',
                'how_to_use' => 'Aplique com um disco de algodão ou com as mãos após a limpeza facial, antes do sérum e do hidratante.',
                'expert_review' => '"Um passo simples mas eficaz para reequilibrar a pele antes dos ativos seguintes da rotina." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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
                'benefits' => ['Limpeza suave do dia-a-dia', 'Enriquecido com óleo de marula', 'Não resseca a pele'],
                'ingredients_list' => 'Base saponificada de óleos vegetais, óleo de marula, manteiga de karité, extrato de cúrcuma.',
                'how_to_use' => 'Aplique sobre o rosto húmido, faça espuma e massaje suavemente. Enxague com água morna.',
                'expert_review' => '"Um sabonete suave, sem sulfatos agressivos, adequado para uso diário mesmo em peles sensíveis." — Dermocosmetóloga da equipa Flor de Marula.',
                'shipping_returns' => 'Entrega em Luanda em 24 a 48 horas úteis. Para outras províncias, 3 a 5 dias úteis. Devoluções aceites até 30 dias após a compra, com o produto por abrir.',
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

        // "Complete Sua Rotina" — sugestao de produto complementar (212:116 > Frame 72)
        $routine = [
            'serum-facial' => 'creme-hidratante',
            'creme-hidratante' => 'protetor-solar',
            'protetor-solar' => 'serum-facial',
            'gel-de-limpeza' => 'tonico-facial',
            'tonico-facial' => 'serum-facial',
            'contorno-de-olhos' => 'creme-hidratante',
            'sabonete-facial' => 'gel-de-limpeza',
        ];
        foreach ($routine as $slug => $routineSlug) {
            Product::where('slug', $slug)->update([
                'routine_product_id' => Product::where('slug', $routineSlug)->value('id'),
            ]);
        }

        // "Produtos Relacionados" (frame "Secção Nosso melhor  / Produtos Relacionados")
        $related = [
            'serum-facial' => ['creme-hidratante', 'protetor-solar'],
            'creme-hidratante' => ['serum-facial', 'protetor-solar'],
            'protetor-solar' => ['serum-facial', 'creme-hidratante'],
            'gel-de-limpeza' => ['tonico-facial', 'sabonete-facial'],
            'tonico-facial' => ['gel-de-limpeza', 'serum-facial'],
            'contorno-de-olhos' => ['serum-facial', 'creme-hidratante'],
            'sabonete-facial' => ['gel-de-limpeza', 'tonico-facial'],
        ];
        foreach ($related as $slug => $relatedSlugs) {
            $product = Product::where('slug', $slug)->first();
            $idsBySlug = Product::whereIn('slug', $relatedSlugs)->pluck('id', 'slug');
            $sync = collect($relatedSlugs)->mapWithKeys(fn ($relatedSlug, $i) => [$idsBySlug[$relatedSlug] => ['sort_order' => $i]])->all();
            $product->relatedProducts()->sync($sync);
        }

        // "Ofertas exclusivas" — precos exatos do Figma para o Sérum Facial (212:110 / Frame 71);
        // demais produtos seguem a mesma estrutura (2 e 6 frascos) com desconto por volume calculado.
        foreach (Product::all() as $product) {
            if ($product->slug === 'serum-facial') {
                $offers = [
                    ['label' => '2 FRASCOS', 'quantity' => 2, 'price' => 19000],
                    ['label' => '6 FRASCOS', 'quantity' => 6, 'price' => 49500],
                ];
            } else {
                $offers = [
                    ['label' => '2 FRASCOS', 'quantity' => 2, 'price' => (int) round($product->price * 2 * 0.95, -2)],
                    ['label' => '6 FRASCOS', 'quantity' => 6, 'price' => (int) round($product->price * 6 * 0.85, -2)],
                ];
            }
            foreach ($offers as $i => $offer) {
                ProductOffer::updateOrCreate(
                    ['product_id' => $product->id, 'quantity' => $offer['quantity']],
                    ['label' => $offer['label'], 'price' => $offer['price'], 'sort_order' => $i]
                );
            }
        }

        // Galeria de miniaturas ("Other images" 161:1482 a 161:1486) — independente da imagem
        // principal (image_path); apenas o Sérum Facial tem galeria definida no frame
        // "Detalhes" do Figma. As miniaturas trocam a imagem principal exibida via JS.
        $serum = Product::where('slug', 'serum-facial')->first();
        $galleryImages = ['serum-facial-thumb-1.png', 'serum-facial-thumb-2.png', 'serum-facial-thumb-3.png', 'serum-facial-thumb-4.png', 'serum-facial-thumb-5.png'];
        $serum->images()->delete();
        foreach ($galleryImages as $i => $file) {
            ProductImage::create([
                'product_id' => $serum->id,
                'sort_order' => $i,
                'image_path' => "images/product/{$file}",
            ]);
        }

        // Ingredientes (Frame 79 a 82) — associados ao Sérum Facial no Figma.
        $ingredients = [
            ['name' => 'Óleo semente da Árvore de Marula', 'slug' => 'oleo-semente-arvore-marula', 'image_path' => 'images/product/ingredient-oleo-marula.png', 'description' => 'Além de hidratar profundamente, seus antioxidantes também atuam em manchas escuras e no tom irregular da pele. Diga adeus à hiperpigmentação e olá a um brilho radiante.'],
            ['name' => 'Vitamina E', 'slug' => 'vitamina-e', 'image_path' => 'images/product/ingredient-vitamina-e.png', 'description' => 'Indispensável para desbotar manchas escuras e prevenir cicatrizes de acne, também protege contra radicais livres. Aproveite a pele mais lisa e uniforme.'],
            ['name' => 'Óleo de rosa e pétalas', 'slug' => 'oleo-de-rosa-e-petalas', 'image_path' => 'images/product/ingredient-oleo-rosa.png', 'description' => 'Reconhecida por equilibrar óleos para a pele, é uma campeã contra acne. Sua ação suave também combate a hiperpigmentação, deixando sua pele clara e luminosa.'],
            ['name' => 'Marinela', 'slug' => 'marinela', 'image_path' => 'images/product/ingredient-marinela.png', 'description' => 'Além de hidratar profundamente, seus antioxidantes também atuam em manchas escuras e no tom irregular da pele. Diga adeus à hiperpigmentação e olá a um brilho radiante.'],
        ];
        foreach ($ingredients as $i => $ingredient) {
            $model = Ingredient::updateOrCreate(['slug' => $ingredient['slug']], $ingredient);
            $serum->ingredients()->syncWithoutDetaching([$model->id => ['sort_order' => $i]]);
        }

        // Perguntas Frequentes (accordion "Perguntas Frequentes") — 3 perguntas genericas por produto.
        foreach (Product::all() as $product) {
            $faqs = [
                ['question' => 'Este produto é seguro durante a gravidez?', 'answer' => 'Sim, todos os produtos Flor de Marula são formulados sem ingredientes contraindicados na gravidez. Em caso de dúvida, consulte o seu médico.'],
                ['question' => 'Quanto tempo demora a entrega?', 'answer' => 'Em Luanda, a entrega demora entre 24 a 48 horas úteis. Para outras províncias, entre 3 a 5 dias úteis.'],
                ['question' => 'Qual é a validade do produto após aberto?', 'answer' => 'Recomendamos utilizar o produto até 12 meses após a abertura, guardado num local fresco e ao abrigo da luz solar direta.'],
            ];
            foreach ($faqs as $i => $faq) {
                Faq::updateOrCreate(
                    ['product_id' => $product->id, 'question' => $faq['question']],
                    ['answer' => $faq['answer'], 'sort_order' => $i]
                );
            }
        }
    }
}
