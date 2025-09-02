<?php

namespace App\Policies;

use App\Models\Product_Variant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class Product_VariantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->isAdmin() || $user->isVendor() ?
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product_Variant $product_Variant): Response
    {
        return $product_Variant !== null && $user->isAdmin() ?
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user,Product $product): Response
    {
        return $product !== null && $user->isAdmin() ?
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product_Variant $productVariant): bool
    {
        return $user->isAdmin() || $user->isVendor() && $productVariant !== null ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product_Variant $productVariant): Response
    {
        return $user->isAdmin() ?
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product_Variant $productVariant): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product_Variant $productVariant): bool
    {
        return false;
    }
}
