<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class CniService
{
    /**
     * Verify a reservation CNI submission.
     *
     * @param int $reservationId
     * @param bool $isApproved
     * @return bool
     */
    public function verifyCNI(int $reservationId, bool $isApproved): bool
    {
        $reservation = Reservation::with('tenant')->findOrFail($reservationId);
        $tenant = $reservation->tenant;
        $cniImagePath = $reservation->cni_image;

        if ($isApproved) {
            if ($tenant) {
                $tenant->update([
                    'is_cni_valid' => true,
                    'cni_image' => $cniImagePath ?? $tenant->cni_image,
                ]);
            }

            $reservation->update(['status' => 'APPROVED']);

            return true;
        }

        if ($cniImagePath) {
            Storage::disk('public')->delete($cniImagePath);
        }

        $reservation->update([
            'status' => 'REJECTED',
            'cni_image' => null,
        ]);

        if ($tenant) {
            $tenantData = ['is_cni_valid' => false];

            if ($tenant->cni_image === $cniImagePath) {
                $tenantData['cni_image'] = null;
            }

            $tenant->update($tenantData);
        }

        return true;
    }

    /**
     * Get all reservations pending CNI validation.
     *
     * @return Collection
     */
    public function getPendingCNIs(): Collection
    {
        return $this->getPendingCnisQuery()
            ->with(['tenant.user', 'field'])
            ->get();
    }

    public function countPendingCNIs(): int
    {
        return $this->getPendingCnisQuery()->count();
    }

    public function getPendingCnisQuery(): Builder
    {
        return Reservation::query()
            ->whereNotNull('cni_image')
            ->where('status', 'PENDING')
            ->where(function ($query) {
                $query->whereNull('tenant_id')
                    ->orWhereHas('tenant', function ($tenantQuery) {
                        $tenantQuery->where('is_cni_valid', false);
                    });
            })
            ->orderByDesc('created_at')
            ;
    }
}
