<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any targets.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the target.
     */
    public function view(User $user, User $target): bool
    {
        return $user->role === 'admin' || $user->id === $target->id;
    }

    /**
     * Determine whether the user can create targets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the target.
     */
    public function update(User $user, User $target): bool
    {
        return $user->role === 'admin' || $user->id === $target->id;
    }

    /**
     * Determine whether the user can delete the target.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->role === 'admin' || $user->id === $target->id;
    }

    /**
     * Determine whether the user can restore the target.
     */
    public function restore(User $user, User $target): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the target.
     */
    public function forceDelete(User $user): bool
    {
        return $user->role === 'admin';
    }
}
