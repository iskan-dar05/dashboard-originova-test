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
    // public function create(User $user): Response
    // {
    //     return $user->create || $user->role === 'admin'
    //         ? Response::allow()
    //         : Response::deny('you dont have permission');
    // }
    // public function update(User $user): Response
    // {
    //     return $user->update || $user->role === 'admin'
    //         ? Response::allow()
    //         : Response::deny('you dont have permission');
    // }
    // public function delete(User $user): Response
    // {
    //     return $user->delete || $user->role === 'admin'
    //         ? Response::allow()
    //         : Response::deny('you dont have permission');
    // }
}
