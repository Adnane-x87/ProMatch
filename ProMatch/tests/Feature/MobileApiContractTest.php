<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MobileApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_accepts_type_or_role_and_login_returns_user_with_sanctum_token(): void
    {
        $this->postJson('/api/register', [
            'first_name' => 'Mobile',
            'last_name' => 'Owner',
            'email' => 'mobile-owner@test.com',
            'password' => 'secret123',
            'phone' => '0600000000',
            'type' => 'owner',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'mobile-owner@test.com')
            ->assertJsonPath('data.user.roles.0.name', 'owner');

        $this->postJson('/api/register', [
            'first_name' => 'Mobile',
            'last_name' => 'Tenant',
            'email' => 'mobile-tenant@test.com',
            'password' => 'secret123',
            'phone' => '0611111111',
            'role' => 'tenant',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.roles.0.name', 'tenant');

        $this->postJson('/api/login', [
            'email' => 'mobile-owner@test.com',
            'password' => 'secret123',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'mobile-owner@test.com')
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_public_field_listing_details_and_available_slots_are_accessible_without_auth(): void
    {
        [$field, $slot] = $this->createFieldWithSlot();

        $this->getJson('/api/public-fields')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['name' => $field->name]);

        $this->getJson("/api/public-fields/{$field->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $field->id)
            ->assertJsonPath('data.time_slots.0.id', $slot->id);

        $this->getJson("/api/available-slots?field_id={$field->id}&date=2026-06-20")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.id', $slot->id);
    }

    public function test_guest_can_create_reservation_with_mobile_payload_and_fake_slot_id(): void
    {
        Storage::fake('public');
        [$field] = $this->createFieldWithSlot();

        $this->postJson('/api/reservations', [
            'terrain_id' => $field->id,
            'time_slot_id' => 9001,
            'date' => '2026-06-21',
            'selected_time' => '18:00',
            'first_name' => 'Guest',
            'last_name' => 'Player',
            'email' => 'guest-player@test.com',
            'phone' => '0622222222',
            'cni_image' => UploadedFile::fake()->create('cni.jpg', 20, 'image/jpeg'),
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'PENDING')
            ->assertJsonPath('data.start_time', '2026-06-21 18:00:00');

        $this->assertDatabaseHas('reservations', [
            'field_id' => $field->id,
            'time_slot_id' => null,
            'email' => 'guest-player@test.com',
            'request_date' => '2026-06-21',
            'start_time' => '2026-06-21 18:00:00',
        ]);
    }

    public function test_admin_mobile_routes_require_token_and_work_with_sanctum_auth(): void
    {
        [$field, $slot] = $this->createFieldWithSlot();
        $ownerUser = $field->owner->user;

        $tenantUser = User::create([
            'first_name' => 'Booked',
            'last_name' => 'Tenant',
            'email' => 'booked-tenant@test.com',
            'password' => Hash::make('password'),
            'phone' => '0633333333',
        ]);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'AA12345']);
        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'time_slot_id' => $slot->id,
            'first_name' => 'Booked',
            'last_name' => 'Tenant',
            'email' => 'booked-tenant@test.com',
            'phone' => '0633333333',
            'request_date' => '2026-06-20',
            'start_time' => '2026-06-20 10:00:00',
            'price' => 120,
            'status' => 'PENDING',
        ]);

        $this->getJson('/api/reservations')
            ->assertUnauthorized()
            ->assertJsonPath('success', false);

        Sanctum::actingAs($ownerUser);

        $this->getJson('/api/reservations')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['email' => 'booked-tenant@test.com']);

        $this->getJson('/api/planning?date=2026-06-20')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson('/api/dashboard/stats')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['active_reservations']]);

        $this->putJson("/api/reservations/{$reservation->id}/validate")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'APPROVED');
    }

    public function test_protected_field_slot_cni_and_logout_routes_work_with_token_auth(): void
    {
        Storage::fake('public');
        [$field] = $this->createFieldWithSlot();
        Sanctum::actingAs($field->owner->user);

        $createdFieldId = $this->postJson('/api/fields', [
            'name' => 'API Mobile Field',
            'description' => 'Created from mobile admin',
            'address' => 'Tangier',
            'price_per_hour' => 150,
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->json('data.id');

        $this->putJson("/api/fields/{$createdFieldId}", [
            'name' => 'API Mobile Field Updated',
            'address' => 'Tangier',
            'price_per_hour' => 175,
        ])->assertOk()
            ->assertJsonPath('data.name', 'API Mobile Field Updated');

        $this->postJson("/api/fields/{$createdFieldId}/slots", [
            'slots' => [[
                'date' => '2026-06-22',
                'start_time' => '12:00',
                'end_time' => '13:00',
            ]],
        ])->assertOk()
            ->assertJsonPath('success', true);

        $slotId = $this->postJson('/api/dashboard/slots', [
            'field_id' => $createdFieldId,
            'date' => '2026-06-23',
            'start_time' => '14:00',
            'end_time' => '15:00',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->json('data.id');

        $this->getJson("/api/dashboard/slots/{$slotId}")
            ->assertOk()
            ->assertJsonPath('data.id', $slotId);

        $this->putJson("/api/dashboard/slots/{$slotId}", [
            'status' => 'UNAVAILABLE',
        ])->assertOk()
            ->assertJsonPath('data.status', 'UNAVAILABLE');

        $this->deleteJson("/api/dashboard/slots/{$slotId}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/cni/upload', [
            'cni_image' => UploadedFile::fake()->create('cni.pdf', 20, 'application/pdf'),
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['path']]);

        $this->deleteJson("/api/fields/{$createdFieldId}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    private function createFieldWithSlot(): array
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'User',
            'email' => uniqid('owner-', true) . '@test.com',
            'password' => Hash::make('password'),
            'phone' => '0600000000',
        ]);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Mobile Contract Field',
            'address' => 'Tangier',
            'price_per_hour' => 120,
        ]);
        $slot = TimeSlot::create([
            'field_id' => $field->id,
            'date' => '2026-06-20',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'AVAILABLE',
        ]);

        return [$field->load('owner.user'), $slot];
    }
}
