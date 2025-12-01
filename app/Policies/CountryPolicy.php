<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CountryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Country');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Country $Country): bool
    {
        return $user->can('View:Country');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Create:Country');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Country $Country): bool
    {
        return $user->can('Update:Country');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Country $Country): bool
    {
        return $user->can('Delete:Country');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Country $Country): bool
    {
        return $user->can('Restore:Country');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Country $Country): bool
    {
        return $user->can('ForceDelete:Country');
    }
}