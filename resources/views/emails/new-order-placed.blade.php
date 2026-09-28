<!doctype html>
<html lang="bs">
<head>
    <meta charset="utf-8">
    <title>Nova narudzba</title>
</head>
<body>
    <h2>Nova narudzba #{{ $order->id }}</h2>

    <p><strong>Kupac:</strong> {{ $order->customer_name }}</p>
    <p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email ?? 'nije unesen' }}</p>

    <h3>Stavke</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item['name'] }} - kolicina: {{ $item['quantity'] }}</li>
        @endforeach
    </ul>

    <p>Status: {{ $order->status }}</p>
    <p>Kreirano: {{ $order->created_at?->format('d.m.Y H:i') }}</p>
</body>
</html>
