<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('view_user') && $this->canManage($user, $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('update_user') && $this->canManage($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Tidak boleh menghapus diri sendiri + Cek Hierarki
        return $user->can('delete_user') && $user->id !== $model->id && $this->canManage($user, $model);
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Tidak boleh menghapus diri sendiri + Cek Hierarki
        return $user->can('force_delete_user') && $user->id !== $model->id && $this->canManage($user, $model);
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->can('restore_user') && $this->canManage($user, $model);
    }

    /**
     * Logika Hierarki Role: Mencegah Role rendah mengelola Role tinggi
     */
    private function canManage(User $user, User $target): bool
    {
        // Super Admin bisa mengelola siapa saja
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Jika target adalah Super Admin, tapi pengelola BUKAN Super Admin -> TOLAK
        if ($target->hasRole('super_admin')) {
            return false;
        }

        // Definisi bobot hierarki
        $weights = [
            'super_admin' => 100,
            'admin'       => 80,
            'treasurer'   => 60,
            'secretary'   => 60,
            'member'      => 10,
        ];

        $userWeight   = $weights[strtolower($user->role)] ?? 0;
        $targetWeight = $weights[strtolower($target->role)] ?? 0;

        // User hanya bisa mengelola role yang setara atau di bawahnya
        return $userWeight >= $targetWeight;
    }
}
