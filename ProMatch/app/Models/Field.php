<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    protected $fillable = ['owner_id', 'name', 'description', 'address', 'price_per_hour'];

    public function owner() { return $this->belongsTo(Owner::class); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class); }
    public function reservations() { return $this->hasManyThrough(Reservation::class, TimeSlot::class); }

    public function getAvailableTimeSlots() {
        return $this->timeSlots()->where('status', 'AVAILABLE')->get();
    }
}