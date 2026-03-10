<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model {
    protected $fillable = ['user_id', 'registration_date'];

    public function user() { return $this->belongsTo(User::class); }
    public function fields() { return $this->hasMany(Field::class); }

    public function reservations() {
        return Reservation::whereHas('timeSlot.field', function ($query) {
            $query->where('owner_id', $this->id);
        });
    }

    public function validateReservation(Reservation $r) {
        $r->confirm();
    }

    public function verifyCNI(string $cniImage): bool {
        // CNI verification logic
        return true;
    }
}
