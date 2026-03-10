<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $fillable = [
        'tenant_id', 'time_slot_id', 'employee_id',
        'first_name', 'last_name', 'email', 'phone',
        'request_date', 'price', 'cni_image', 'status'
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function timeSlot() { return $this->belongsTo(TimeSlot::class); }
    public function employee() { return $this->belongsTo(Employee::class); }

    public function confirm() {
        $this->update(['status' => 'APPROVED']);
        $this->timeSlot->update(['status' => 'RESERVED']);
    }

    public function cancel() {
        $this->update(['status' => 'CANCELED']);
        $this->timeSlot->update(['status' => 'AVAILABLE']);
    }
}