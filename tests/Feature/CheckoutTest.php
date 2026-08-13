<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithGuestSession;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithGuestSession;

    public function test_checkout_is_blocked_when_cart_is_empty(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
    }

    public function test_completing_checkout_creates_an_order_with_correct_totals_and_deducts_stock(): void
    {
        $product = Product::factory()->create(['price' => 10000, 'stock' => 50]);
        $addResponse = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $this->carrySessionCookie($addResponse);

        $response = $this->post(route('checkout.store'), [
            'name' => 'Maria Fernandes',
            'email' => 'maria@example.com',
            'phone' => '+244923456789',
            'address_line' => 'Rua das Flores, 123',
            'city' => 'Talatona',
            'province' => 'Luanda',
            'shipping_method' => 'luanda',
            'payment_method' => 'entrega',
        ]);

        $order = Order::first();

        $this->assertNotNull($order, 'Order was not created — cart was likely empty at checkout time.');
        $response->assertRedirect(route('order.show', $order));
        $this->assertSame(10000, $order->subtotal);
        $this->assertSame(2000, $order->shipping_cost);
        $this->assertSame(12000, $order->total);
        $this->assertSame('Pagamento pendente', $order->status);

        $this->assertSame(49, $product->fresh()->stock);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_checkout_requires_all_mandatory_fields(): void
    {
        $product = Product::factory()->create();
        $addResponse = $this->post(route('cart.add'), ['product_id' => $product->id]);
        $this->carrySessionCookie($addResponse);

        $response = $this->post(route('checkout.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'address_line', 'city', 'province', 'shipping_method', 'payment_method']);
        $this->assertDatabaseCount('orders', 0);
    }
}
