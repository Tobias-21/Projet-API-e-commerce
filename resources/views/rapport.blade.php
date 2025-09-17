<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Commandes - Admin</title>
    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 30px;
            color: #333;
        }
        .rapport-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 900px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 10px;
        }
        .rapport-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .rapport-header .date {
            font-size: 1rem;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 8px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }
        th {
            background: #007bff;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f2f6fc;
        }
        .total-row td {
            font-weight: bold;
            background: #e2eafc;
        }
        .footer {
            text-align: right;
            font-size: 0.95rem;
            color: #888;
            margin-top: 30px;
        }

    </style>
    
</head>

<body>
    <div class="rapport-container">
        <h1>Rapport des Commandes</h1>
        <div class="rapport-header">
            <div>Admin: {{ Auth::user()->name }}</div>
            <div class="date">Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                    <th>Status</th>
                   

                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @foreach ($order->order_items as $itemIndex => $item)
                        <tr>
                            <td>{{ $loop->parent->iteration }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $item->product_variant->product->title }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unity_price, 2, ',', ' ') }} XOF</td>

                            @if ($itemIndex === 0)
                                <td rowspan="{{ $order->order_items->count() }}">
                                    {{ number_format($order->total, 2, ',', ' ') }} XOF
                                </td>
                                <td rowspan="{{ $order->order_items->count() }}">
                                    {{ ucfirst($order->payment_status) }}
                                </td>
                            @endif
                           
                        </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Aucune commande trouvée.</td>
                </tr>
                @endforelse

                @if(count($orders) > 0)
                <tr class="total-row">
                    <td colspan="5">Total des commandes</td>
                    <td colspan="2">
                        {{ number_format($orders->sum('total'), 2, ',', ' ') }} XOF
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="footer">
            Rapport généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>