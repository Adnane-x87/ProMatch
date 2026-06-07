<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Owner;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiReservationSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_payload_creates_reservation_visible_to_shared_api(): void
    {
        Storage::fake('public');

        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Reservations',
            'email' => 'owner-reservation@test.com',
            'password' => 'password',
            'phone' => '0600000000',
        ]);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Reservation Sync Field',
            'address' => 'Tangier',
            'price_per_hour' => 220,
        ]);

        $mobileUser = User::create([
            'first_name' => 'Mobile',
            'last_name' => 'Client',
            'email' => 'mobile-client@test.com',
            'password' => 'password',
            'phone' => '0611111111',
        ]);
        Sanctum::actingAs($mobileUser);

        $this->postJson('/api/reservations', [
            'fieldId' => $field->id,
            'reservationDate' => '2026-06-12',
            'startTime' => '18:00',
            'phoneNumber' => '0622222222',
            'cni_image' => 'data:image/png;base64,' . base64_encode('fake-mobile-cni'),
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'PENDING')
            ->assertJsonPath('data.start_time', '2026-06-12 18:00:00');

        $this->assertDatabaseHas('reservations', [
            'field_id' => $field->id,
            'first_name' => 'Mobile',
            'last_name' => 'Client',
            'email' => 'mobile-client@test.com',
            'phone' => '0622222222',
            'request_date' => '2026-06-12',
            'start_time' => '2026-06-12 18:00:00',
            'status' => 'PENDING',
        ]);

        $reservation = \App\Models\Reservation::firstOrFail();
        $this->assertStringStartsWith('reservations/cnis/', $reservation->makeVisible('cni_image')->cni_image);
        Storage::disk('public')->assertExists($reservation->cni_image);

        $this->getJson('/api/reservations')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['email' => 'mobile-client@test.com']);
    }

    public function test_available_slots_accepts_mobile_field_id_parameter(): void
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Slots',
            'email' => 'owner-mobile-slots@test.com',
            'password' => 'password',
            'phone' => '0600000001',
        ]);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Mobile Slot Field',
            'address' => 'Marrakech',
            'price_per_hour' => 180,
        ]);
        TimeSlot::create([
            'field_id' => $field->id,
            'date' => '2026-06-13',
            'start_time' => '19:00',
            'end_time' => '20:00',
            'status' => 'AVAILABLE',
        ]);

        $this->getJson("/api/available-slots?fieldId={$field->id}&date=2026-06-13")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.field_id', $field->id);
    }
}
