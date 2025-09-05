<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product__variant_id','quantity','unity_price'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product_variant(){
        return $this->belongsTo(Product_Variant::class,'product__variant_id');
    }
}
