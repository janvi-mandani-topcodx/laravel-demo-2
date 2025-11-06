<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $Order;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->Order = $orderRepository;
    }

    public function paymentSuccess()
    {
        dump("sdfd");
    }
    public function index(Request $request)
    {
        $orders = Order::where('user_id' , auth()->id())->get();
        if ($request->ajax()) {
            return DataTables::of(Order::get())
                ->editColumn('user name' ,  function ($order) {
                   $name = json_decode($order->shipping_details);
                   $fullName = $name->first_name . ' ' . $name->last_name;
                   return $fullName;
                })
                ->editColumn('refunded Amount' ,  function ($order) {
                    $orderPayments = $order->orderPayments->first();

                    return $orderPayments->refunded_amount;
                })
                ->make(true);
        }
        return view('order.index' , compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $allCartData = \Cart::getContent();
        dd($allCartData);
        $charge = auth()->user()->charge($input['total']*100 , $input['paymentMethodId'] , [
            'return_url' => 'http://127.0.0.1:8000',
            'description' => auth()->user()->full_name,
        ]);
        if($charge->status == 'succeeded'){
            $this->Order->store($input , $charge);
            return redirect()->route('order.index');
        }
    }

    public function show(string $id)
    {
        $order = Order::find($id);
        $orderDetails = json_decode($order->shipping_details);
        return view('order.show' , compact('order' , 'orderDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orderData = Order::with(['orderItems' , 'orderPayments'])->find($id);
        return view('order.edit' , compact('orderData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $order  = Order::find($id);

        $orderPayments = $order->orderPayments;
        $orderUser = $order->user;
        $orderItems = $order->orderItems;

        $totalSum = $orderPayments->sum('amount');
        $paymentMethod = $orderUser->paymentMethods()->first();


        if($totalSum < $input['total']){
            $amount = $input['total'] - $totalSum;
            $charge = $orderUser->charge($amount * 100 , $paymentMethod->id  , [
                'return_url' => 'http://127.0.0.1:8000',
                'description' => auth()->user()->full_name,
            ]);
            $order->orderPayments()->create([
                'payment_id' => $charge->id,
                'amount' => $charge->amount / 100,
                'refunded_amount' => 0,
            ]);

            $order->total = $totalSum + $amount;
            $order->save();

        }
        else if($totalSum > $input['total']){
            $amount = $totalSum - $input['total'];
            $totalRefundAmount = $amount * 100;
            foreach ($orderPayments as $orderPayment) {

                $alreadyRefundAmount = $orderPayment->refunded_amount * 100;
                $refundAmount = ($orderPayment->amount * 100) - $alreadyRefundAmount;

                if ($refundAmount <= 0) {
                    continue;
                }
                $refundedAmount = min($totalRefundAmount ,$refundAmount);
                $orderUser->refund($orderPayment->payment_id, [
                    'amount' => $refundedAmount,
                ]);

                $orderPayment->refunded_amount = $orderPayment->refunded_amount + ($refundedAmount / 100);
                $orderPayment->save();


                $order->total = $totalSum -  $amount;
                $order->save();

                $totalRefundAmount -= $refundedAmount;

                if ($totalRefundAmount <= 0) {
                    break;
                }
            }
        }
        $this->Order->update($input  , $order );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchProductItems(Request $request)
    {
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $products = Product::where('title', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                ->get();
        }
        else{
            $products = Product::all();
        }
        if ($request->ajax()) {
            $html = '';
            foreach ($products as $product) {
                foreach ($product->productVariants as $productVariant) {
                    $html .= '<div class="row bg-light w-50 product-'.$product->id.'-'.$productVariant->id.' rounded my-2 border single-product" id="singleProduct" data-id="'. $product->id .'" data-variant="'.$productVariant->id.'" data-url="'.$product->image_url[0].'">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <img src="'.$product->image_url[0].'" width="100" height="100" class="product-image" data-url="'.$product->image_url[0].'">
                                            </div>
                                            <div class="col">
                                                 <p class="product-title-search">'. $product->title.'</p>
                                                 <p class="variant-size-search">Size : '. $productVariant->title.'</p>
                                            </div>
                                            <div class="col">
                                                <p>$</p>
                                                <p class="price-search">'. $productVariant->price .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ';
                }
            }
            return response()->json(['html' => $html]);
        }
    }

    public function orderDetailsEdit(Request $request)
    {
        $input = $request->all();
        $order = Order::find($input['edit_id']);
        $shipping = Arr::only($input, ['first_name', 'last_name', 'address', 'state' , 'country']);
        $shippingDetails = json_encode($shipping);
        $order->update([
            'user_id' => auth()->id(),
            'shipping_details' => $shippingDetails,
            'delivery' => $input['delivery'],
            'total' => $order->total,
        ]);
    }
}
