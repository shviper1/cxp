<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Section');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Section $Section): bool
    {
        return $user->can('View:Section');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Create:Section');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Section $Section): bool
    {
        return $user->can('Update:Section');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Section $Section): bool
    {
        return $user->can('Delete:Section');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Section $Section): bool
    {
        return $user->can('Restore:Section');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Section $Section): bool
    {
        return $user->can('ForceDelete:Section');
    }
}