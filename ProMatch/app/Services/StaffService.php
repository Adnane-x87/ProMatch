<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class StaffService
{
    /**
     * Get the schedule for today's reservations.
     *
     * @return Collection
     */
    public function getDailySchedule(?int $employeeId = null): Collection
    {
        $today = Carbon::today()->toDateString();
        
        return Reservation::with(['tenant.user', 'field', 'timeSlot'])
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->whereIn('status', ['APPROVED', 'ARRIVED', 'ABSENT'])
            ->where(function ($query) use ($today) {
                $query->whereDate('start_time', $today)
                    ->orWhereHas('timeSlot', function ($q) use ($today) {
                        $q->whereDate('date', $today);
                    });
            })
            ->get()
            ->sortBy(function ($reservation) {
                if ($reservation->start_time) {
                    return Carbon::parse($reservation->start_time)->format('H:i:s');
                }
                if ($reservation->timeSlot) {
                    return $reservation->timeSlot->start_time;
                }
                return '00:00:00';
            })
            ->values();
    }

    /**
     * Verify that a client has arrived for their reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function verifyClientArrival(int $reservationId, ?int $employeeId = null): bool
    {
        $reservation = $this->findEmployeeReservation($reservationId, $employeeId);
        
        if ($reservation->status === 'APPROVED') {
            return $reservation->update(['status' => 'ARRIVED']);
        }

        return false;
    }

    /**
     * Verify that a client was absent for their reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function verifyClientAbsent(int $reservationId, ?int $employeeId = null): bool
    {
        $reservation = $this->findEmployeeReservation($reservationId, $employeeId);
        
        if ($reservation->status === 'APPROVED') {
            return $reservation->update(['status' => 'ABSENT']);
        }

        return false;
    }

    /**
     * Get daily dashboard statistics.
     *
     * @return array
     */
    public function getDailyStats(?int $employeeId = null): array
    {
        $reservations = $this->getDailySchedule($employeeId);

        return [
            'total' => $reservations->count(),
            'arrived' => $reservations->where('status', 'ARRIVED')->count(),
            'absent' => $reservations->where('status', 'ABSENT')->count(),
        ];
    }

    private function findEmployeeReservation(int $reservationId, ?int $employeeId): Reservation
    {
        return Reservation::query()
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->findOrFail($reservationId);
    }
}
