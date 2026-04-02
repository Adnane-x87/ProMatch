<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservationService $reservationService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->reservationService = new ReservationService();
    }

    public function test_can_get_all_reservations()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);
        
        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't@t.com', 'password' => '1', 'phone' => '2', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'cin1', 'birth_date' => '2000-01-01']);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jon',
            'last_name' => 'Doe',
            'email' => 'jon@doe.com',
            'phone' => '123',
            'request_date' => now(),
            'start_time' => '2024-05-20 10:00:00',
            'end_time' => '2024-05-20 11:00:00',
            'price' => 100,
            'status' => 'APPROVED'
        ]);

        $reservations = $this->reservationService->getAllReservations();
        $this->assertCount(1, $reservations);
        $this->assertEquals('F1', $reservations->first()->field->name);
    }

    public function test_can_cancel_reservation()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_cancel@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c1', 'birth_date' => '2000-01-01']);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => '2024-05-20 12:00:00',
            'end_time' => '2024-05-20 13:00:00',
            'price' => 120,
            'status' => 'APPROVED'
        ]);

        $this->reservationService->cancelReservation($reservation->id);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'CANCELED'
        ]);
    }

    public function test_can_reject_reservation()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_reject@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c2', 'birth_date' => '2000-01-01']);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => '2024-05-20 12:00:00',
            'end_time' => '2024-05-20 13:00:00',
            'price' => 120,
            'status' => 'PENDING'
        ]);

        $this->reservationService->rejectReservation($reservation->id);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'REJECTED'
        ]);
    }
}
