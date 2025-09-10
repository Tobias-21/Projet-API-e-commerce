<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Shipping::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'carrier' => 'required|string',
            'tracking_number' => 'required|string',
        ]);

        $order = Order::where('id',$request->order_id)->first();

        $shipping = Shipping::create([
            'order_id' => $order->id,
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Livraison en cours',
            'shipping' => $shipping
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipping $shipping)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipping $shipping)
    {
        Gate::authorize('update', $shipping);

        $request->validate([
            'status' => 'required|in:pending,in_transit,delivered'
        ]);

        $shipping = Shipping::where('id',$shipping->id)->first();
        
        $shipping->update([
            'status' => $request->status
        ]);

        $shipping->order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'shipping_status' => $shipping->status,
            'shipping' => $shipping->load('order')
        ]);
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping)
    {
        //
    }
}
