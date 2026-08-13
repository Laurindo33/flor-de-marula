<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithGuestSession;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithGuestSession;

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create();

        $addResponse = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $addResponse->assertRedirect();
        $this->carrySessionCookie($addResponse);

        $this->get(route('cart.index'))->assertSee($product->name);
        $this->assertDatabaseCount('carts', 1);
    }

    public function test_adding_the_same_product_twice_merges_quantity_instead_of_duplicating(): void
    {
        $product = Product::factory()->create(['price' => 10000]);

        $first = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $this->carrySessionCookie($first);

        $this->post(route('cart.add'), ['product_id' => $product->id]);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_cart_totals_apply_percentual_coupon_discount(): void
    {
        $product = Product::factory()->create(['price' => 10000]);
        Coupon::create([
            'code' => 'DESCONTO10',
            'type' => 'percentual',
            'value' => 10,
            'active' => true,
        ]);

        $addResponse = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $this->carrySessionCookie($addResponse);

        $couponResponse = $this->post(route('cart.coupon.apply'), ['code' => 'desconto10']);
        $this->carrySessionCookie($couponResponse);

        $response = $this->get(route('cart.index'));

        $response->assertSee('1.000kz', false);
        $response->assertSee('9.000kz', false);
    }

    public function test_removing_a_cart_item_deletes_it(): void
    {
        $product = Product::factory()->create();
        $addResponse = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $this->carrySessionCookie($addResponse);

        $item = CartItem::first();
        $this->delete(route('cart.remove', $item));

        $this->assertDatabaseCount('cart_items', 0);
    }
}
