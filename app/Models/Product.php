<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'base_price', 'category_id', 'sku', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Product_Images::class);
    }

    public function variants()
    {
        return $this->hasMany(Product_Variant::class,'product__variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
