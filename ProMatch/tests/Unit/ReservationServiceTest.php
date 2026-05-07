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
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'cin1']);

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

    public function test_create_reservation_creates_missing_tenant_for_authenticated_user()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'owner-booking@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F2', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 150]);

        $user = User::create([
            'first_name' => 'Karim',
            'last_name' => 'Naciri',
            'email' => 'karim-auth@t.com',
            'password' => '1',
            'phone' => '0600000001',
            'type' => 'tenant',
        ]);

        $reservation = $this->reservationService->createReservation([
            'field_id' => $field->id,
            'date' => '2026-04-21',
            'selected_time' => '2026-04-21 20:00:00',
            'first_name' => 'Karim',
            'last_name' => 'Naciri',
            'email' => 'karim-auth@t.com',
            'phone' => '0600000001',
        ], $user);

        $user->refresh();

        $this->assertNotNull($user->tenant);
        $this->assertEquals($user->tenant->id, $reservation->tenant_id);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'tenant_id' => $user->tenant->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_create_reservation_creates_guest_user_and_tenant_when_needed()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'owner-guest@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F3', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 250]);

        $reservation = $this->reservationService->createReservation([
            'field_id' => $field->id,
            'date' => '2026-04-21',
            'selected_time' => '2026-04-21 20:00:00',
            'first_name' => 'Karim',
            'last_name' => 'Naciri',
            'email' => 'karim@example.com',
            'phone' => '0600000003',
            'cni_image' => 'reservations/cnis/test.jpg',
        ]);

        $guestUser = User::where('email', 'karim@example.com')->first();

        $this->assertNotNull($guestUser);
        $this->assertNotNull($guestUser->tenant);
        $this->assertEquals($guestUser->tenant->id, $reservation->tenant_id);
        $this->assertDatabaseHas('tenants', [
            'user_id' => $guestUser->id,
            'cni_image' => null,
        ]);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'tenant_id' => $guestUser->tenant->id,
            'email' => 'karim@example.com',
            'status' => 'PENDING',
        ]);
    }

    public function test_can_cancel_reservation()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_cancel@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c1']);

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
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c2']);

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
