<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model {
    protected $fillable = ['field_id', 'date', 'start_time', 'end_time', 'status'];

    public function field() { return $this->belongsTo(Field::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }
}