<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = ['order_id','tracking_number','status','carrier'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
