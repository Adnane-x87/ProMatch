<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'tenant_id', 'field_id', 'employee_id', 'time_slot_id',
        'first_name', 'last_name', 'email', 'phone',
        'request_date', 'start_time', 'end_time', 'price',
        'cni_image', 'status'
    ];

    protected $hidden = [
        'cni_image',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function field()
    {
        return $this->belongsTo(Field::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function confirm()
    {
        $this->update(['status' => 'APPROVED']);
    }

    public function cancel()
    {
        $this->update(['status' => 'CANCELED']);
    }
}
