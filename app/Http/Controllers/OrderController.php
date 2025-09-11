<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\OrderItem;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     *  @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index()
    {
        Gate::authorize('viewAny',Order::class);

        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
       Gate::authorize('create',Order::class);

        $validOrder = $request->validated();
        $user = Auth::user();

        $cart = $user->cart;
       // var_dump($cart->cart_items->product_variant);

        if (!$cart || !$cart->cart_items()->exists()) {
            return response()->json([
                'message' => 'Votre panier est vide.'
            ], 400);
        }

        
        $total = $cart->cart_items()->sum('price');

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'payment_status' => 'unpaid',
            'shipping_address' => $request->shipping_address
        ]);

        foreach($cart->cart_items as $item){
            //if (!$item->product_variant) continue;

            OrderItem::create([
                'order_id' => $order->id,
                'product__variant_id' => $item->product__variant_id,
                'quantity' => $item->quantity,
                'unity_price' => $item->product_variant->price
            ]);
        }

       // $cart->cart_items()->delete();

        return \response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load(['order_items','order_items.product_variant'])
        ]);
   
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        Gate::authorize('view',$order);

        return $order;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
