<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_product_page_is_publicly_visible(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $this->get(route('product.show', $product))->assertOk();
    }

    public function test_inactive_product_page_returns_404(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $this->get(route('product.show', $product))->assertNotFound();
    }

    public function test_inactive_product_is_excluded_from_shop_listing(): void
    {
        $active = Product::factory()->create(['is_active' => true, 'name' => 'Produto Visível']);
        $inactive = Product::factory()->create(['is_active' => false, 'name' => 'Produto Escondido']);

        $response = $this->get(route('shop.index'));

        $response->assertSee('Produto Visível');
        $response->assertDontSee('Produto Escondido');
    }
}
