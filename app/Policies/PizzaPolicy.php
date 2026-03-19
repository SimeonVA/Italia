<?php

namespace App\Policies;

use App\Models\Pizza;
use App\Models\User;

class PizzaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Iedereen mag de kaart zien
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Pizza $pizza): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Pizza $pizza): bool
    {
        return $user->is_admin;
    }
}