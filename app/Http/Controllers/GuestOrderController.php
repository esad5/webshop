<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderPlaced;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GuestOrderController extends Controller
{
    private const PRODUCTS = [
        'wc-tipka-4u1' => 'WC Tipka za Ispiranje - 4u1 Set',
        'nosac-wc-skoljke' => 'Nosač WC Školjke - Set za Montažu',
        'tece-tipka-bijela' => 'TECE WC Tipka - Bijela',
    ];

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'items_json' => ['required', 'json'],
        ]);

        $items = json_decode($validated['items_json'], true);

        Validator::make(['items' => $items], [
            'items' => ['required', 'array', 'min:1', 'max:10'],
            'items.*.id' => ['required', 'string', 'in:'.implode(',', array_keys(self::PRODUCTS))],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ])->validate();

        $orderItems = collect($items)
            ->groupBy('id')
            ->map(fn ($productItems, $id) => [
                'id' => $id,
                'name' => self::PRODUCTS[$id],
                'quantity' => $productItems->sum('quantity'),
            ])
            ->values()
            ->all();

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'items' => $orderItems,
        ]);

        $recipient = config('mail.order_notification_to');
        if ($recipient) {
            Mail::to($recipient)->send(new NewOrderPlaced($order));
        }

        return redirect()->route('dashboard')->with(
            'order_success',
            'Hvala, narudžba #'.$order->id.' je zaprimljena. Kontaktirat ćemo vas radi potvrde.'
        );
    }
}