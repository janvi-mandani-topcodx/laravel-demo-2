<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $fillable = [
        'title',
        'description',
        'status'
    ];

    public function productVariants(){
        return $this->hasMany(ProductVariant::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }


    public function getImageUrlAttribute()
    {
        $img = [];
        $productImage = $this->getMedia('product');
        if($productImage){
            foreach ($productImage as $image) {
                $img[] =  $image->getUrl();
            }
            return $img;
        }
        return null;
    }
}
