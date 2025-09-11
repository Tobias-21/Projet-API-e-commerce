<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $nb_commande = Order::count();
        $nb_sale = Order::where('payment_status','paid')->count();

        return \response()->json([
            'number_commande' => $nb_commande,
            'number_sale' => $nb_sale
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function rapport()
    {
        $orders = Order::with('user','order_items.product_variant.product')->get();

        $path = public_path('rapports');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return Pdf::view('rapport',['orders' => $orders])->save($path . '/rapport.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
