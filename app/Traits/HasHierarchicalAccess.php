<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait HasHierarchicalAccess
{
    /**
     * Cek apakah user punya akses berdasarkan hierarki: All > Divisi > Own
     */
    protected function hasPermissionWithHierarchy(User $user, string $action, string $resourcePermissionName, ?Model $record = null): bool
    {
        // 1. ALL: Jika punya izin '_all', maka bisa akses semuanya
        if ($user->can("{$action}_all_{$resourcePermissionName}")) {
            return true;
        }

        // Jika tidak ada record (misal: viewAny), cukup cek apakah dia punya salah satu izin dasar
        if (!$record) {
            return $user->can("view_any_{$resourcePermissionName}");
        }

        // 2. DIVISI: Jika punya izin '_divisi', cek apakah divisinya sama
        if ($user->can("{$action}_divisi_{$resourcePermissionName}")) {
            $recordDivisiId = $record->divisi_id ?? ($record->user->divisi_id ?? ($record->pembuat->divisi_id ?? null));
            if ($recordDivisiId && $user->divisi_id === $recordDivisiId) {
                return true;
            }
        }

        // 3. OWN: Jika punya izin dasar (misal: 'update'), cek apakah dia pembuatnya
        if ($user->can("{$action}_{$resourcePermissionName}")) {
            $ownerId = $record->user_id ?? ($record->id_pembuat ?? ($record->created_by ?? null));
            if ($ownerId && $user->id === $ownerId) {
                return true;
            }
        }

        return false;
    }
}
