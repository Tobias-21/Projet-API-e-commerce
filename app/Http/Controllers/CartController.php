<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Cart_Item;
use App\Models\Product_Variant;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     */

    public function index()
    {
        Gate::authorize('viewAny',Cart::class);
        return Cart::with('cart_items')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create',Cart::class);

        $request->validate([
            'product__variant_id' => 'required|exists:product__variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $variant = Product_Variant::find($request->product__variant_id);

        $cart_item = $cart->cart_items()->where('product__variant_id', $variant->id)->first();

        if($cart_item){
            $item_quantity = $cart_item->quantity + $request->quantity;

            if($item_quantity > $variant->stock ){
                return \response()->json([
                    'message' => 'Insufficient stock available'
                ], 400);
            }

            $price = $variant->price * $item_quantity;   
            
            $cart_item->update([
                'quantity' => $item_quantity,
                'price' => $price
            ]);
            
            return response()->json([
                'message' => 'Cart updated successfully',
                'cart' => $cart->load('cart_items')
            ], 200);

        }else{

            if($request->quantity > $variant->stock ){
                return \response()->json([
                    'message' => 'Insufficient stock available'
                ], 400);
            }

            $price = $variant->price * $request->quantity;

            $cart->cart_items()->create([
                'product__variant_id' => $variant->id,
                'quantity' => $request->quantity,
                'price' => $price
            ]);
        }
                    
        return response()->json([
            'message' => 'Cart created successfully',
            'cart' => $cart->load('cart_items')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart_Item $item)
    {
       
        Gate::authorize('update',$item->cart);        

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = Product_Variant::find($item->product__variant_id);

        if($request->quantity > $variant->stock){
            return response()->json([
                'message' => 'Insufficient stock available'
            ], 400);
        }

        $price = $variant->price * $request->quantity; 

        $item->update([
            'quantity' => $request->quantity,
            'price' => $price
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart' => $item->cart->load('cart_items')
        ], 200);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart_Item $item)
    {
        Gate::authorize('delete',$item->cart);

        $item->delete();
        return response()->json([
            'message' => 'Cart item removed successfully',
        ],200);
    }
}
