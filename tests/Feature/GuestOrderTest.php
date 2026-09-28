<?php

namespace Tests\Feature;

use App\Mail\NewOrderPlaced;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GuestOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_open_the_catalog(): void
    {
        $this->get('/')->assertOk()->assertSee('AI 3D PRINT');
        $this->get('/dashboard')->assertOk();
    }

    public function test_guest_can_place_an_order_without_an_account(): void
    {
        config(['mail.order_notification_to' => 'owner@example.com']);
        Mail::fake();

        $response = $this->post(route('orders.store'), [
            'customer_name' => 'Amira Example',
            'customer_phone' => '+387 61 123 456',
            'customer_email' => 'amira@example.com',
            'items_json' => json_encode([
                ['id' => 'wc-tipka-4u1', 'quantity' => 2],
                ['id' => 'tece-tipka-bijela', 'quantity' => 1],
            ]),
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('order_success');
        $this->assertGuest();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Amira Example',
            'customer_phone' => '+387 61 123 456',
        ]);

        $order = Order::query()->firstOrFail();
        $this->assertSame(2, $order->items[0]['quantity']);
        $this->assertSame('TECE WC Tipka - Bijela', $order->items[1]['name']);
        Mail::assertSent(NewOrderPlaced::class, function (NewOrderPlaced $mail) use ($order) {
            return $mail->hasTo('owner@example.com') && $mail->order->is($order);
        });
    }

    public function test_guest_cannot_order_an_unknown_product(): void
    {
        $response = $this->from('/')->post(route('orders.store'), [
            'customer_name' => 'Amira Example',
            'customer_phone' => '+387 61 123 456',
            'items_json' => json_encode([
                ['id' => 'unknown-product', 'quantity' => 1],
            ]),
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('orders', 0);
    }
}