<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    
    
    public function cliente(User $user): bool
    {
        return $user->role === 3 ;
    }
    

    public function bibliotecario(User $user): bool
    {
        return $user->role === 2 ;
    }


    public function admin(User $user): bool
    {
        return $user->role === 1 ;
    }
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
 
    }
}
