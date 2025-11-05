<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    public $fillable = [
        'order_id',
        'amount',
        'discount_name',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

}
