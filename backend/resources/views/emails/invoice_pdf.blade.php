<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 14px; margin: 20px; }
h2 { margin-bottom: 10px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
table, th, td { border: 1px solid #333; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
.total { margin-top: 15px; font-weight: bold; font-size: 16px; }
</style>
</head>
<body>
<h2>Facture de la commande</h2>
<p>Client: {{ $order->user->full_name  }}</p>
<p>Adresse:
    {{ $order->shippingAddress->street ?? '' }},
    {{ $order->shippingAddress->city ?? '' }}
</p>

<table>
<thead>
<tr>
<th>Produit</th>
<th>Variation</th>
<th>Quantité</th>
<th>Prix (MAD)</th>
<th>Sous-total (MAD)</th>
</tr>
</thead>
<tbody>
@foreach($order->orderItems as $item)
<tr>
<td>{{ $item->productVariant->product->name }}</td>
<td>{{ $item->productVariant->size ?? '' }} / {{ $item->productVariant->color ?? '' }}</td>
<td>{{ $item->quantity }}</td>
<td>{{ $item->price }}</td>
<td>{{ $item->quantity * $item->price }}</td>
</tr>
@endforeach
</tbody>
</table>

<p class="total">Total: {{ $order->total_price }} MAD</p>
</body>
</html>
