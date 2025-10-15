<?php

namespace App\Repositories;
use App\Mail\EmailVerificationMail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UsersDemo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductRepository extends BaseRepository
{
    public function model()
    {
        return Product::class;
    }

    public function store($data)
    {
        $media = Arr::only($data, 'image');
        $product = Arr::only($data, ['title', 'description' , 'status']);
        $variant = Arr::only($data, ['variant_title', 'price', 'sku']);


        if (isset($product['status'])) {
            $product['status'] = 1;
        } else {
            $product['status'] = 0;
        }

        $product = Product::create($product);
        if ($media) {
            foreach ($media['image'] as $file) {
                $product->addMedia($file)->toMediaCollection('product');
            }
        }
        $this->storeVariant($variant, $product);
    }

    public function storeVariant($variantData, $product)
    {
        foreach ($variantData['variant_title'] as $key => $value) {
                $product->productVariants()->updateOrCreate(['id' => $variantData['edit_id'][$key] ?? null] ,[
                    'title' => $variantData['variant_title'][$key],
                    'price' => $variantData['price'][$key],
                    'sku' => $variantData['sku'][$key],
                ]);
        }
    }

    public function update($input, $data)
    {
        $product = Arr::only($input, ['title', 'description' , 'status']);
        $variant = Arr::only($input, ['variant_title', 'price', 'sku' , 'edit_id']);

        $deleteImg = $data->getMedia('product');
        if($input->hasFile('image')){
            foreach ($input['image'] as $file) {
                $data->addMedia($file)->toMediaCollection('product');
            }
            if($deleteImg){
                foreach ($deleteImg as $images){
                    $images->delete();
                }
            }
        }
        $data->update($product);

        $this->storeVariant($variant, $data);

    }


}
