<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    protected $fillable = ['owner_id', 'name', 'description', 'address', 'price_per_hour', 'image'];
    protected $appends = ['image_url'];

    public function owner() { return $this->belongsTo(Owner::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class); }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return str_contains($this->image, '/')
            ? asset('storage/' . $this->image)
            : asset('images/fields/' . $this->image);
    }
}
