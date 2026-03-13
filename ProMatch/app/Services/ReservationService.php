<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    /**
     * Get all reservations with related details.
     *
     * @return Collection
     */
    public function getAllReservations(): Collection
    {
        return Reservation::with(['tenant.user', 'employee.user', 'field'])->get();
    }

    /**
     * Cancel a specific reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function cancelReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'CANCELED']);
        return true;
    }

    /**
     * Reject a specific reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function rejectReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'REJECTED']);
        return true;
    }

    /**
     * Explicitly validate a reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function validateReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'APPROVED']);
        return true;
    }
}
