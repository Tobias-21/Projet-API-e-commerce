<?php

namespace App\Http\Controllers;

use App\Models\Product_Variant;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVariantRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Http\Requests\UpdateVariantRequest;

class ProductVariantsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        Gate::authorize('viewAny',Product_Variant::class);

        return Product_Variant::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariantRequest $request, Product $product)
    {
        Gate::authorize('create',$product);

        $variantValidate = $request->validated();
        $variantValidate['attributes'] = json_decode($variantValidate['attributes'], true);

        $variant = Product_Variant::create([
            'product_id' => $product->id,
            'attributes' => $variantValidate['attributes'],
            'price' => $variantValidate['price'],
            'sku' => $variantValidate['sku'],
            'stock' => $variantValidate['stock'],
        ]);

        return \response()->json([
            'message' => 'Variant created successfully',
            'variant' => $variant
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product_Variant $variant)
    {

        Gate::authorize('view',$variant);

        return $variant;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariantRequest $request, Product_Variant $variant)
    {
        
        Gate::authorize('update',$variant);

        $VariantUpdate = $request->validated();

        $VariantUpdate['attributes'] = json_decode($VariantUpdate['attributes'], true);

        $variant->update($VariantUpdate);

        return \response()->json([
            'message' => 'Variant updated successfully',
            'variant' => $variant
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product_Variant $variant)
    {
        Gate::authorize('delete',$variant);

        $variant->delete();

        return \response()->json([
            'message' => 'Variant deleted successfully'
        ]);
    }
}
