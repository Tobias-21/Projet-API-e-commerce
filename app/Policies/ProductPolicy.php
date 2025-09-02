<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {

        return $user !== null? 
            Response::allow() :
            Response::deny('Unauthentificate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): Response
    {
        return $user !== null ? 
            Response::allow() :
            Response::deny('Unauthentificate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin() || $user->isVendor() ? 
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): Response 
    {
        return $user !== null && $user->id === $product->user_id ? 
            Response::allow() :
            Response::deny('Permission denied');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): Response
    {
        return $user !== null && ($user->id === $product->user_id || $user->isAdmin())? 
            Response::allow() :
            Response::deny('Permission denied');
            
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }
}
