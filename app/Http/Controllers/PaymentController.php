<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
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
       
        $order = Order::where('id', $request->order_id)->firstOrFail();
        
        if(Auth::id() !== $order->user_id){
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:credit_cart,mobile_money,bank_tansfert',
        ]);  
        
            $payment = Payment::create([
                'order_id' => $order->id,
                'provider' => "PAL",
                'method' => $request->method,
                'amount' => $order->total,
                'status' => "pending",
                'transaction_ref' => uniqid("tob_")
            ]);

        return $this->initiate($payment);

    }

    public function initiate($payment) {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.api_pal.secret_key') . ':' . config('services.api_pal.public_key'),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post(config('services.api_pal.base_url') . '/checkout/requesttopup', [
            'user_id' => config('services.api_pal.user_id'),
            'amount' => (float) $payment->amount,
            'currency' => "XOF",
            'email' => $payment->order->user->email,
            'phone' => "+2290146346595",
            "callback_url" => route('payment.callback')
        ]);

        $body = $response->json();

        if ($response->failed() || !isset($body['data'])) {
            return response()->json([
                'message' => 'Erreur API PAL',
                'details' => $response->json()
            ], 500);
        }
        //reponse PAL

        $payment->update([
            'provider_ref' => $body['data']['reference']
        ]);

        return response()->json([
            'message'      => 'Payment init successfully',
            'payment'      => $payment,
            'redirect_url' => $body['data']
        ], 201);
    }
    
    public function status($order_id) {

        $order = Order::find($order_id);

        if(!$order){
            return response()->json([
                'message' => 'Commande not found'
            ]);
        }

        $user = User::find($order->user_id);

        if(Auth::id() !== $user->id && (!$user->isAdmin() || !$user->isVendor())){
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }

        return response()->json([
            'status_payment' => $order->payment_status
        ]);
    }

    public function callback(Request $request) {
     
       // \Log::info('PAL CALLBACK RAW', $request->all());
        $data = $request->all();

        if (!isset($data['reference'])) {
            return response()->json(['message' => 'Invalid callback data'], 400);
        }

        $payment = Payment::where('provider_ref', $data['reference'])->first();

        if(!$payment){
            return \response()->json([
                'message' => 'Payment not found'
            ],404);
        }

        $newStatus = match ($data['status']) {
            'success' => 'success',
            'failed'  => 'failed',
            'canceled'=> 'canceled',
            default   => 'pending'
        };

        $payment->update([
            'status' => $newStatus
        ]);

        if($newStatus === 'success'){
            $payment->order->update([
                'payment_status' => 'paid'
            ]);
        }

        return response()->json([
            'message' => 'Callback receved and payment status updated',
            'payment' => $payment->load('order')
        ],201);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
