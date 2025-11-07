<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditOrderRequest;
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
    private $orderRepo;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepo = $orderRepository;
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
                   return auth()->user()->full_name;
                })
                ->editColumn('refunded Amount' ,  function ($order) {
                    $orderPayments = $order->orderPayments->first();

                    return $orderPayments->refunded_amount;
                })
                ->make(true);
        }
        return view('order.index' , compact('orders'));
    }



    public function show(string $id)
    {
        $order = Order::find($id);
        $orderDetails = json_decode($order->shipping_details);
        return view('order.show' , compact('order' , 'orderDetails'));
    }

    public function edit(string $id)
    {
        $orderData = Order::with(['orderItems' , 'orderPayments'])->find($id);
        if(auth()->user()->hasPermissionTo('update_order')) {
            return view('order.edit' , compact('orderData'));
        }
        else{
            return view('order.index');
        }
    }

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
        $this->orderRepo->update($input  , $order );

    }


    public function destroy(string $id)
    {
        if(auth()->user()->hasPermissionTo('delete_order')) {
            Order::find($id)->delete();
        }
        else{
            return view('order.index');
        }
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
                    $html .= '<div class="row bg-light border rounded w-100 product-'.$product->id.'-'.$productVariant->id.'  my-2  single-product" id="singleProduct" data-id="'. $product->id .'" data-variant="'.$productVariant->id.'" data-url="'.$product->image_url[0].'">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <img src="'.$product->image_url[0].'" width="100" height="100" class="product-image" data-url="'.$product->image_url[0].'">
                                            </div>
                                            <div class="col">
                                                 <p class="product-title-search">'. $product->title.'</p>
                                                 <p class="variant-size-search"> '. $productVariant->title.'</p>
                                            </div>
                                            <div class="col d-flex">
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

    public function orderDetailsEdit(EditOrderRequest $request)
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

    public function selectSingleProduct(Request $request)
    {
        $input = $request->all();
        $order = Order::find($input['order_id']);
        $orderItems = $order->orderItems;
        $existingOrderItem = null;
        foreach ($orderItems as $item) {
            if ($item->product_id == $input['product_id'] && $item->variant_id == $input['variant_id']) {
                $existingOrderItem = $item;
                break;
            }
        }

        if($existingOrderItem){
            return response()->json([
                'status' => 'exist',
                'quantity' => $existingOrderItem->quantity + 1,
            ]);
        }
        else{
            return response()->json([
               'status' => 'not_exist',
            ]);
        }

    }
}
