<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     *  @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        Gate::authorize('viewAny',Category::class);
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Gate::authorize('create',Category::class);
        
        $validateCategory = $request->validated();

        $category = Category::create($validateCategory);

        return \response()->json([
            'category' =>  $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('delete',$category);

        $category->delete();

        return \response()->json([
            'message' => 'Categoty deleted successfully'
        ]);
    }
}
