<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product_Images extends Model
{
     use HasFactory;
    
    protected $fillable = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
