<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Owner;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffServiceTest extends TestCase
{
    use RefreshDatabase;

    private StaffService $staffService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->staffService = new StaffService();
    }

    private function createOwner()
    {
        $u = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'owner' . uniqid() . '@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        return Owner::create(['user_id' => $u->id, 'registration_date' => now()]);
    }

    private function createEmployee()
    {
        $user = User::create(['first_name' => 'E', 'last_name' => 'U', 'email' => 'employee' . uniqid() . '@t.com', 'password' => '1', 'phone' => '1', 'type' => 'employee']);

        return Employee::create(['user_id' => $user->id, 'position' => 'Staff', 'hire_date' => now()]);
    }

    public function test_can_get_daily_schedule()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C1']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->toDateString() . ' 10:00:00',
            'end_time' => now()->toDateString() . ' 11:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $schedule = $this->staffService->getDailySchedule();
        $this->assertCount(1, $schedule);
    }

    public function test_daily_schedule_does_not_use_request_date_as_reservation_date()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenantFuture@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'CF']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'future@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->addWeek()->toDateString() . ' 10:00:00',
            'end_time' => now()->addWeek()->toDateString() . ' 11:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $schedule = $this->staffService->getDailySchedule();
        $this->assertCount(0, $schedule);
    }

    public function test_daily_schedule_only_shows_accepted_reservations()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenantPending@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'CP']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'pending@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->toDateString() . ' 10:00:00',
            'end_time' => now()->toDateString() . ' 11:00:00',
            'price' => 50,
            'status' => 'PENDING'
        ]);

        $schedule = $this->staffService->getDailySchedule();
        $this->assertCount(0, $schedule);
    }

    public function test_can_verify_client_arrival()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant2@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C1']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->toDateString() . ' 10:00:00',
            'end_time' => now()->toDateString() . ' 11:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $result = $this->staffService->verifyClientArrival($reservation->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'ARRIVED'
        ]);
    }

    public function test_can_verify_client_absence()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant3@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C2']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->toDateString() . ' 12:00:00',
            'end_time' => now()->toDateString() . ' 13:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $result = $this->staffService->verifyClientAbsent($reservation->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'ABSENT'
        ]);
    }

    public function test_employee_cannot_verify_another_employees_reservation()
    {
        $owner = $this->createOwner();
        $employee = $this->createEmployee();
        $otherEmployee = $this->createEmployee();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant4@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C4']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'employee_id' => $employee->id,
            'field_id' => $field->id,
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'j4@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => now()->toDateString() . ' 12:00:00',
            'end_time' => now()->toDateString() . ' 13:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->staffService->verifyClientArrival($reservation->id, $otherEmployee->id);
    }
}
