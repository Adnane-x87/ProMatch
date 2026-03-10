<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
    protected $fillable = ['user_id', 'position', 'hire_date'];

    public function user() { return $this->belongsTo(User::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    public function verifyClientArrival(Reservation $r) {
        $r->update(['status' => 'APPROVED']);
    }

    public function viewSchedule() {
        return $this->reservations()->with('timeSlot')->get();
    }
}

