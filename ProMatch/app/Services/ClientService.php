<?php

namespace App\Services;

use App\Models\Owner;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    /**
     * Get all clients (tenants) with their user relation,
     * reservation counts and total spend.
     *
     * @return Collection
     */
    public function getAllClients(): Collection
    {
        return Tenant::with('user')
            ->withCount('reservations')
            ->withSum('reservations', 'price')
            ->get();
    }

    /**
     * Find a tenant by ID with its user relation.
     *
     * @param int $tenantId
     * @return Tenant
     */
    public function getTenantById(int $tenantId): Tenant
    {
        return Tenant::with('user')->findOrFail($tenantId);
    }

    /**
     * Block a tenant's user account.
     *
     * @param int $tenantId
     * @return void
     */
    public function blockClient(int $tenantId): void
    {
        $tenant = $this->getTenantById($tenantId);
        $user   = $tenant->user;
        $owner  = Owner::first();

        if ($owner) {
            $owner->blockUser($user);
        } else {
            $user->is_blocked = true;
            $user->save();
        }
    }

    /**
     * Unblock a tenant's user account.
     *
     * @param int $tenantId
     * @return void
     */
    public function unblockClient(int $tenantId): void
    {
        $tenant = $this->getTenantById($tenantId);
        $user   = $tenant->user;
        $owner  = Owner::first();

        if ($owner) {
            $owner->unblockUser($user);
        } else {
            $user->is_blocked = false;
            $user->save();
        }
    }

    /**
     * Mark a tenant's CNI as validated.
     *
     * @param int $tenantId
     * @return void
     */
    public function validateCni(int $tenantId): void
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->is_cni_valid = true;
        $tenant->save();
    }
}
