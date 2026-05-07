<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationService
{
    /**
     * UC6: Create a new reservation (guest or logged-in user).
     */
    public function createReservation(array $data, $user = null): Reservation
    {
        return DB::transaction(function () use ($data, $user) {
            $tenant = $this->resolveTenant($data, $user);
            $field = Field::find($data['field_id']);
            $price = $field ? $field->price_per_hour : ($data['price'] ?? 0);
            $cniImagePath = $this->storeCniImage($data, $tenant);

            return Reservation::create([
                'field_id' => $data['field_id'],
                'time_slot_id' => $data['time_slot_id'] ?? null,
                'tenant_id' => $tenant->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? 'guest_' . time() . '@promatch.ma',
                'phone' => $data['phone'],
                'request_date' => $data['date'],
                'start_time' => $data['selected_time'] ?? null,
                'price' => $price,
                'cni_image' => $cniImagePath,
                'status' => 'PENDING',
            ]);
        });
    }

    private function storeCniImage(array $data, ?Tenant $tenant = null): ?string
    {
        $cniImage = $data['cni_image'] ?? null;

        if ($cniImage instanceof UploadedFile) {
            $path = $cniImage->store('reservations/cnis', 'public');

            if ($tenant && !$tenant->cni_image) {
                $tenant->update(['cni_image' => $path]);
            }

            return $path;
        }

        return is_string($cniImage) ? $cniImage : null;
    }

    private function resolveTenant(array $data, $user = null): Tenant
    {
        if ($user?->tenant) {
            return $user->tenant;
        }

        $account = $user;

        if (!$account && !empty($data['email'])) {
            $account = User::where('email', $data['email'])->first();
        }

        if (!$account) {
            $account = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? ('guest_' . Str::lower(Str::random(12)) . '@promatch.local'),
                'password' => Hash::make(Str::random(32)),
                'phone' => $data['phone'] ?? '',
                'type' => 'tenant',
            ]);
        }

        return Tenant::firstOrCreate(
            ['user_id' => $account->id],
            ['cin' => $data['cin'] ?? $data['tenant_cin'] ?? $this->generatePlaceholderCin($account)]
        );
    }

    private function generatePlaceholderCin(User $user): string
    {
        return 'TMP-' . $user->id . '-' . Str::upper(Str::random(8));
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
            $query->whereDate('request_date', $date);
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
