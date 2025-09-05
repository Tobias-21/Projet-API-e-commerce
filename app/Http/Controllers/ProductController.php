<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index(Request $request)
    {
        Gate::authorize('viewAny',Product::class);

        $product = Product::with(['category', 'images', 'variants']);

        if($request->has('category')){
            $product->whereHas('category', function($query) use ($request){
                $query->where('name', $request->category);
            });
        }

        if($request->has('status')){
            $product->where('status', $request->status);
        }

        if($request->has('attributes')){
            $attributes = json_decode($request->input('attributes'), true);
            $product->whereHas('variants', function($query) use ($attributes){
                foreach($attributes as $key => $value){
                    $query->whereJsonContains("attributes->$key", $value);
                }
            });
        }

        $search = $request->input('search');
        if($search){
            $product->where(function($query) use ($search){
                $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like' , "%{$search}%")
                        ->orWhere('sku', 'like' , "%{$search}%");
            });
        }

        return $product->latest()->paginate(10);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create',Product::class);

        $request->validated();

        $products = Product::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'status' => $request->status,
        ]);

        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $image = $image->store('products','public');
                $products->images()->create([
                    'url' => Storage::url($image),
                ]);
            }
        }
        
        return \response()->json([
            'message' => 'Product created successfully',
            'product' => $products->load(['category', 'images', 'variants']),
           
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view',$product);

        return $product->load(['category', 'images', 'variants']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update',$product);
       
       $productValidate = $request->validated();
        // dd($productValidate);
        $product->update($productValidate);

        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $image = $image->store('products','public');
                $product->images()->create([
                    'url' => Storage::url($image),
                ]);
            }
        }
        return \response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['category', 'images', 'variants']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
       Gate::authorize('delete',$product);
        
        $product->delete();

        return \response()->json([
            'message' => 'Product deleted successfully'
        ],200);
    
    }
}
