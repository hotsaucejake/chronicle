<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerbsDocument;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VerbsDocument $document): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VerbsDocument $document): bool
    {
        return !$document->is_locked;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VerbsDocument $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VerbsDocument $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VerbsDocument $document): bool
    {
        return false;
    }
}
