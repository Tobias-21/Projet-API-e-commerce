<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'provider', 'method', 'amount', 'status', 'transaction_ref','provider_ref'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];


    public function order(){
        return $this->belongsTo(Order::class);
    }
}
