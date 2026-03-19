<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        // Iedereen mag de lijst zien, maar we filteren de inhoud in de Resource!
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        // Alleen eigen orders bekijken, tenzij je admin bent
        return $user->is_admin || $order->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // Iedereen mag bestellen
    }

    public function update(User $user, Order $order): bool
    {
        // Alleen eigen orders aanpassen, tenzij je admin bent
        return $user->is_admin || $order->user_id === $user->id;
    }

    public function delete(User $user, Order $order): bool
    {
        // Alleen de admin mag orders echt weggooien
        return $user->is_admin;
    }
}