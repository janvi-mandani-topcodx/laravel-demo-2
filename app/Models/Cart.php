<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     public $fillable = [
         'user_id',
         'product_id',
         'variant_id',
         'quantity',
     ];

     public function product(){
         return $this->belongsTo(Product::class);
     }
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class , 'variant_id');
    }
}
