<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    private $productRepo;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    public function index(Request $request)
    {
        $product = Product::all();
        if ($request->ajax()) {
            return DataTables::of(Product::get())
                ->editColumn('status', function ($product) {
                    if ($product->status == 1) {
                        return 'Active';
                    }
                    else{
                        return 'Inactive';
                    }
                })
                ->make(true);
        }
        return view('products.index', compact('product'));
    }

    public function create()
    {
        if(auth()->user()->hasPermissionTo('create_product')) {
            return view('products.create');
        }
        else{
            return  view('products.index');
        }
    }

    public function store(CreateProductRequest $request)
    {
        $input = $request->all();
        $this->productRepo->store($input);
        return redirect()->route('product.index');
    }


    public function edit(string $id)
    {
        $product = Product::find($id);
        if(auth()->user()->hasPermissionTo('update_product')) {
            return view('products.edit', compact('product'));
        }
        else{
            return  view('products.index');
        }
    }


    public function update(UpdateProductRequest $request, string $id)
    {
        $input = $request->all();
        $product = Product::find($id);
        $this->productRepo->update($input , $product);
        return redirect()->route('product.index');
    }


    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(auth()->user()->hasPermissionTo('create_permission')) {

            $product->delete();
            $deleteImg = $product->getMedia('product');
            $product->productVariants()->delete();
            if ($deleteImg) {
                foreach ($deleteImg as $img) {
                    $img->delete();
                }
            }
        }
        else{
            return  view('products.index');
        }
    }

    public function deleteVariant(Request $request)
    {
        $input = $request->all();
        ProductVariant::find($input['delete_id'])->delete();
    }
}
