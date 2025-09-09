<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','total','status','payment_status','shipping_address'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }

    public function shipping(){
        return $this->hasOne(Shipping::class);
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

}
