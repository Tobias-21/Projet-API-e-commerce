<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product_Variant extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_id', 'attributes', 'price', 'stock', 'sku'];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

    public function cart_items(){
        return $this->hasMany(Cart_Item::class,'product__variant_id');
    }
}
