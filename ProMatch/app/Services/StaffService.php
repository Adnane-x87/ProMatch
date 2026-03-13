<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class StaffService
{
    /**
     * Get the schedule for today's reservations.
     *
     * @return Collection
     */
    public function getDailySchedule(): Collection
    {
        return Reservation::with(['tenant.user', 'field'])
            ->whereDate('start_time', now()->toDateString())
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * Verify that a client has arrived for their reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function verifyClientArrival(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        // Mark as approved/confirmed upon arrival if it was already approved
        if ($reservation->status === 'APPROVED') {
            // Here you might want to add a specific 'ARRIVED' status or similar
            // For now, let's just return true if it's a valid approved reservation being checked in
            return true; 
        }

        return false;
    }
}
