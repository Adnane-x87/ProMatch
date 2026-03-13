<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class PublicReservationService
{
    /**
     * Create a new reservation for a tenant.
     *
     * @param int $tenantId
     * @param int $fieldId
     * @param array $data
     * @return Reservation
     */
    public function reserve(int $tenantId, int $fieldId, array $data): Reservation
    {
        return Reservation::create([
            'tenant_id' => $tenantId,
            'field_id' => $fieldId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'request_date' => now(),
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'price' => $data['price'],
            'cni_url' => $data['cni_url'] ?? null,
            'status' => 'PENDING',
        ]);
    }

    /**
     * Get reservation history for a specific tenant.
     *
     * @param int $tenantId
     * @return Collection
     */
    public function getTenantHistory(int $tenantId): Collection
    {
        return Reservation::with(['field'])
            ->where('tenant_id', $tenantId)
            ->orderBy('request_date', 'desc')
            ->get();
    }

    /**
     * Cancel a reservation by the tenant.
     *
     * @param int $reservationId
     * @return bool
     */
    public function cancelReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        // Logic check: only pending or approved reservations can be canceled by user
        if (in_array($reservation->status, ['PENDING', 'APPROVED'])) {
            return $reservation->update(['status' => 'CANCELED']);
        }

        return false;
    }
}
