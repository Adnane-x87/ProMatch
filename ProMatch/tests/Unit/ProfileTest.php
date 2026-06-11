<?php

namespace Tests\Unit;

use App\Models\Owner;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    private function createOwner()
    {
        $u = User::create([
            'first_name' => 'O',
            'last_name' => 'U',
            'email' => 'owner@t.com',
            'password' => bcrypt('password'),
            'phone' => '1',
        ]);
        return Owner::create(['user_id' => $u->id, 'registration_date' => now()]);
    }

    public function test_profile_redirects_to_login_when_unauthenticated()
    {
        $response = $this->get(route('profile'));
        $response->assertRedirect(route('login'));
    }

    public function test_profile_loads_and_displays_reservations()
    {
        $owner = $this->createOwner();
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '12345',
        ]);
        $tenant = Tenant::create(['user_id' => $user->id]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Terrain Super',
            'address' => 'A1',
            'price_per_hour' => 100
        ]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '12345',
            'request_date' => now(),
            'start_time' => '2026-05-20 18:00:00',
            'end_time' => '2026-05-20 19:00:00',
            'price' => 100,
            'status' => 'PENDING'
        ]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Terrain Super');
        $response->assertSee('En attente');
    }

    public function test_user_can_cancel_own_reservation()
    {
        $owner = $this->createOwner();
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '12345',
        ]);
        $tenant = Tenant::create(['user_id' => $user->id]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Terrain Super',
            'address' => 'A1',
            'price_per_hour' => 100
        ]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '12345',
            'request_date' => now(),
            'start_time' => '2026-05-20 18:00:00',
            'end_time' => '2026-05-20 19:00:00',
            'price' => 100,
            'status' => 'PENDING'
        ]);

        $response = $this->actingAs($user)->post(route('profile.reservations.cancel', $reservation->id));

        $response->assertRedirect();
        $this->assertEquals('CANCELED', $reservation->fresh()->status);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'CANCELED',
        ]);
    }

    public function test_user_cannot_cancel_other_users_reservation()
    {
        $owner = $this->createOwner();
        $user1 = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '12345',
        ]);
        $tenant1 = Tenant::create(['user_id' => $user1->id]);

        $user2 = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone' => '67890',
        ]);
        $tenant2 = Tenant::create(['user_id' => $user2->id]);

        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Terrain Super',
            'address' => 'A1',
            'price_per_hour' => 100
        ]);

        $reservation = Reservation::create([
            'tenant_id' => $tenant1->id,
            'field_id' => $field->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '12345',
            'request_date' => now(),
            'start_time' => '2026-05-20 18:00:00',
            'end_time' => '2026-05-20 19:00:00',
            'price' => 100,
            'status' => 'PENDING'
        ]);

        $response = $this->actingAs($user2)->post(route('profile.reservations.cancel', $reservation->id));

        $response->assertStatus(403);
        $this->assertEquals('PENDING', $reservation->fresh()->status);
    }
}
