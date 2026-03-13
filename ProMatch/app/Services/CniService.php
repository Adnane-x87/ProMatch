<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class CniService
{
    /**
     * Verify a tenant's CNI.
     *
     * @param int $tenantId
     * @param bool $isApproved
     * @return bool
     */
    public function verifyCNI(int $tenantId, bool $isApproved): bool
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->update(['is_cni_valid' => $isApproved]);
        
        // Optionally, if rejected, clean up the URL
        if (!$isApproved) {
            $tenant->update(['cni_document_url' => null]);
        }
        
        return true;
    }

    /**
     * Get all tenants pending CNI validation.
     *
     * @return Collection
     */
    public function getPendingCNIs(): Collection
    {
        return Tenant::with('user')
            ->whereNotNull('cni_document_url')
            ->where('is_cni_valid', false)
            ->get();
    }
}
