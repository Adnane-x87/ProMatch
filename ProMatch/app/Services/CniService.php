<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

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

        // Optionally, if rejected, clean up the image file
        if (!$isApproved) {
            if ($tenant->cni_image) {
                Storage::disk('public')->delete($tenant->cni_image);
            }
            $tenant->update(['cni_image' => null]);
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
            ->whereNotNull('cni_image')
            ->where('is_cni_valid', false)
            ->get();
    }
}
