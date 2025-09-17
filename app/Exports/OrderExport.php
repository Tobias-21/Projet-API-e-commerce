<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\withHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user','order_items.product_variant.product')->get();
    }

    public function map($order): array
    {
        $rows = [];

        foreach ($order->order_items as $item) {
            $rows[] = [
                $order->user->name ?? 'N/A',                             // Client
                $item->product_variant->product->title ?? 'N/A',          // Produit
                $item->quantity,                                         // Quantité
                $item->unity_price,                                       // Prix unitaire
                $item->quantity * $item->unity_price,                     // Prix total
                $order->payment_status,
                                       
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Client',
            'Produit',
            'Quantité',
            'Prix unitaire',
            'Prix total',
            'Statut du paiement'
        ];
    }
}
