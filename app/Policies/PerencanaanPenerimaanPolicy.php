<?php

namespace App\Policies;

use App\Models\PerencanaanPenerimaan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PerencanaanPenerimaanPolicy
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
    public function view(User $user, PerencanaanPenerimaan $perencanaanPenerimaan): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama'])
            || $perencanaanPenerimaan->divisi_id === $user->divisi_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama', 'staff_keuangan']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PerencanaanPenerimaan $perencanaanPenerimaan): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama'])
            || $perencanaanPenerimaan->divisi_id === $user->divisi_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PerencanaanPenerimaan $perencanaanPenerimaan): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama'])
            || $perencanaanPenerimaan->divisi_id === $user->divisi_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PerencanaanPenerimaan $perencanaanPenerimaan): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PerencanaanPenerimaan $perencanaanPenerimaan): bool
    {
        return $user->hasAnyRole(['direktur_keuangan', 'direktur_utama']);
    }
}
