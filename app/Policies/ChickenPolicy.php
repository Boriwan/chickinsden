<?php

namespace App\Policies;

use App\Models\Chicken;
use App\Models\User;

class ChickenPolicy
{
    /**
     * Everyone signed in may browse the chicken list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Owners may see their own chickens, admins may see all of them.
     */
    public function view(User $user, Chicken $chicken): bool
    {
        return $user->is_admin || $chicken->user_id === $user->id;
    }

    /**
     * Any signed-in user may add a chicken for themselves.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Owners may edit their own chickens, admins may edit anyone's.
     */
    public function update(User $user, Chicken $chicken): bool
    {
        return $user->is_admin || $chicken->user_id === $user->id;
    }

    /**
     * Owners may delete their own chickens, admins may delete anyone's.
     */
    public function delete(User $user, Chicken $chicken): bool
    {
        return $user->is_admin || $chicken->user_id === $user->id;
    }
}
