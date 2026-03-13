<?php

namespace Tests\Unit;

use App\Models\Owner;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Tenant;
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
        return Owner::create(['user_id' => $u->id, 'registration_date' => now(), 'siret' => 'siret' . uniqid()]);
    }

    public function test_can_get_daily_schedule()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C1', 'birth_date' => '1990-01-01']);
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

    public function test_can_verify_client_arrival()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant2@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C1', 'birth_date' => '1990-01-01']);
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
    }
}
