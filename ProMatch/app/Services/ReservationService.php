<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    /**
     * UC6: Create a new reservation (guest or logged-in user).
     */
    public function createReservation(array $data, $user = null): Reservation
    {
        // Map frontend field names to DB columns
        $reservation = Reservation::create([
            'field_id'     => $data['field_id'],
            'time_slot_id' => $data['time_slot_id'] ?? null,
            'tenant_id'    => $user?->tenant?->id ?? null,
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'phone'        => $data['phone'],
            'request_date' => $data['date'],
            'start_time'   => $data['selected_time'] ?? null,
            'cni_image'    => $data['cni_image_base64'] ?? null,
            'status'       => 'PENDING',
        ]);

        return $reservation;
    }

    /**
     * Get available (not yet booked) time slots for a field on a given date.
     */
    public function getAvailableSlots(int $fieldId, string $date): Collection
    {
        // Find time_slot IDs already reserved on that date for that field
        $bookedSlotIds = Reservation::where('field_id', $fieldId)
            ->where('request_date', $date)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->pluck('time_slot_id')
            ->filter()
            ->toArray();

        // Return slots for that field/date that are not booked
        return \App\Models\TimeSlot::where('field_id', $fieldId)
            ->where('date', $date)
            ->whereNotIn('id', $bookedSlotIds)
            ->get();
    }

    /**
     * UC4: Admin updates the status of a reservation.
     */
    public function updateStatus(int $id, string $status): Reservation
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => strtoupper($status)]);
        return $reservation;
    }

    /**
     * UC9: Get all reservations for a given date (daily planning view).
     */
    public function getDailyPlanning(?string $date): Collection
    {
        $query = Reservation::with(['field']);

        if ($date) {
            $query->where('request_date', $date);
        }

        return $query->orderBy('start_time')->get();
    }

    /**
     * Get all reservations with related details.
     */
    public function getAllReservations(): Collection
    {
        return Reservation::with(['tenant.user', 'employee.user', 'field'])->get();
    }

    /**
     * Cancel a specific reservation.
     */
    public function cancelReservation(int $reservationId): bool
    {
        Reservation::findOrFail($reservationId)->update(['status' => 'CANCELED']);
        return true;
    }

    /**
     * Reject a specific reservation.
     */
    public function rejectReservation(int $reservationId): bool
    {
        Reservation::findOrFail($reservationId)->update(['status' => 'REJECTED']);
        return true;
    }

    /**
     * Explicitly validate a reservation.
     */
    public function validateReservation(int $reservationId): bool
    {
        Reservation::findOrFail($reservationId)->update(['status' => 'APPROVED']);
        return true;
    }
}
