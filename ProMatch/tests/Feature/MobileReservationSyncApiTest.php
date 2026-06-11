<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MobileReservationSyncApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_reservation_is_visible_to_owner_mobile_dashboard(): void
    {
        [$ownerUser, $field, $slot] = $this->createOwnerFieldAndSlot();

        $tenantUser = User::create([
            'first_name' => 'Mobile',
            'last_name' => 'Tenant',
            'email' => 'mobile-sync-tenant@test.com',
            'password' => Hash::make('password'),
            'phone' => '0611111111',
        ]);
        Tenant::create(['user_id' => $tenantUser->id]);

        Sanctum::actingAs($tenantUser);

        $this->postJson('/api/mobile/reservations', [
            'field_id' => $field->id,
            'time_slot_id' => $slot->id,
            'date' => '2026-06-24',
            'selected_time' => '18:00',
            'phone' => '0611111111',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'PENDING');

        Sanctum::actingAs($ownerUser);

        $this->getJson('/api/mobile/admin/reservations?date=2026-06-24')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['email' => 'mobile-sync-tenant@test.com'])
            ->assertJsonFragment(['name' => 'Sync API Field']);
    }

    public function test_web_created_reservation_is_visible_to_tenant_mobile_list(): void
    {
        [, $field, $slot] = $this->createOwnerFieldAndSlot();

        $tenantUser = User::create([
            'first_name' => 'Web',
            'last_name' => 'Tenant',
            'email' => 'web-sync-tenant@test.com',
            'password' => Hash::make('password'),
            'phone' => '0622222222',
        ]);
        $tenant = Tenant::create(['user_id' => $tenantUser->id]);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'time_slot_id' => $slot->id,
            'first_name' => 'Web',
            'last_name' => 'Tenant',
            'email' => 'web-sync-tenant@test.com',
            'phone' => '0622222222',
            'request_date' => '2026-06-25',
            'start_time' => '2026-06-25 20:00:00',
            'price' => 200,
            'status' => 'APPROVED',
        ]);

        Sanctum::actingAs($tenantUser);

        $this->getJson('/api/mobile/reservations?date=2026-06-25')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['email' => 'web-sync-tenant@test.com'])
            ->assertJsonFragment(['status' => 'APPROVED'])
            ->assertJsonFragment(['name' => 'Sync API Field']);
    }

    private function createOwnerFieldAndSlot(): array
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Sync',
            'email' => uniqid('sync-owner-', true) . '@test.com',
            'password' => Hash::make('password'),
            'phone' => '0600000000',
        ]);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Sync API Field',
            'address' => 'Tangier',
            'price_per_hour' => 200,
        ]);
        $slot = TimeSlot::create([
            'field_id' => $field->id,
            'date' => '2026-06-24',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'AVAILABLE',
        ]);

        return [$ownerUser, $field, $slot];
    }
}
