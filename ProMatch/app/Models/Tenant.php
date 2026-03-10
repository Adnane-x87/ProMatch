<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model {
    protected $fillable = ['user_id', 'cin', 'birth_date'];

    public function user() { return $this->belongsTo(User::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    public function searchField() {
        return Field::with('timeSlots')->get();
    }

    public function reserve(TimeSlot $slot, array $payload): Reservation {
        return Reservation::create([
            'tenant_id'    => $this->id,
            'time_slot_id' => $slot->id,
            'request_date' => now(),
            ...$payload
        ]);
    }

    public function cancelReservation(Reservation $r) {
        $r->cancel();
    }
}