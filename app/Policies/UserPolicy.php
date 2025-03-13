<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, User $model): bool
    {
        return $user->instansi_id == $model->instansi_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, User $model): bool
    {
        return $user->instansi_id == $model->instansi_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, User $model): bool
    {
        return $user->instansi_id == $model->instansi_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $user, User $model): bool
    {
        return $user->instansi_id == $model->instansi_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $user, User $model): bool
    {
        return $user->instansi_id == $model->instansi_id;
    }
}
