<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    protected $fillable = ['owner_id', 'name', 'description', 'address', 'price_per_hour', 'image'];

    public function owner() { return $this->belongsTo(Owner::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }


}