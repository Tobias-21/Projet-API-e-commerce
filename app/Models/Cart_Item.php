<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart_Item extends Model
{
    protected $fillable = ['carts_id', 'product_variants_id', 'quantity', 'price'];

    public function cart() {
        return $this->belongsTo(cart::class, 'carts_id');
    }
}
