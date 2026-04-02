<?php

namespace Tests\Unit;

use App\Models\Owner;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\User;
use App\Services\PublicReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    private PublicReservationService $reservationService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->reservationService = new PublicReservationService();
    }

    private function createOwner()
    {
        $u = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'owner' . uniqid() . '@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        return Owner::create(['user_id' => $u->id, 'registration_date' => now()]);
    }

    public function test_can_make_reservation()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $user->id, 'cin' => 'C1', 'birth_date' => '1990-01-01']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);

        $data = [
            'first_name' => 'Jon',
            'last_name' => 'Doe',
            'email' => 'jon@doe.com',
            'phone' => '12345',
            'start_time' => '2024-06-01 10:00:00',
            'end_time' => '2024-06-01 11:00:00',
            'price' => 50,
            'cni_image' => \Illuminate\Http\UploadedFile::fake()->image('cni.jpg')
        ];

        $reservation = $this->reservationService->reserve($tenant->id, $field->id, $data);

        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'PENDING']);
    }

    public function test_can_get_tenant_history()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant2@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
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
            'start_time' => '2024-06-01 10:00:00',
            'end_time' => '2024-06-01 11:00:00',
            'price' => 50,
            'status' => 'APPROVED'
        ]);

        $history = $this->reservationService->getTenantHistory($tenant->id);
        $this->assertCount(1, $history);
    }

    public function test_can_cancel_reservation()
    {
        $owner = $this->createOwner();
        $user = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 'tenant3@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
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
            'start_time' => '2024-06-01 10:00:00',
            'end_time' => '2024-06-01 11:00:00',
            'price' => 50,
            'status' => 'PENDING'
        ]);

        $result = $this->reservationService->cancelReservation($reservation->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'CANCELED']);
    }
}
