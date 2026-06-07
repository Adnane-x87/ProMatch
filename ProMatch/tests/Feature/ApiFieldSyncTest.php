<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiFieldSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_created_field_is_visible_to_public_mobile_list(): void
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'User',
            'email' => 'owner-sync@test.com',
            'password' => 'password',
            'phone' => '0600000000',
        ]);
        Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        Sanctum::actingAs($ownerUser);

        $createResponse = $this->postJson('/api/fields', [
            'name' => 'Mobile Sync Field',
            'description' => 'Created from the API',
            'address' => 'Casablanca',
            'price_per_hour' => 180,
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Mobile Sync Field');

        $this->getJson('/api/public-fields')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['name' => 'Mobile Sync Field']);
    }

    public function test_api_slots_are_visible_on_public_field_details(): void
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Slots',
            'email' => 'owner-slots@test.com',
            'password' => 'password',
            'phone' => '0600000001',
        ]);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create([
            'owner_id' => $owner->id,
            'name' => 'Slot Sync Field',
            'address' => 'Rabat',
            'price_per_hour' => 200,
        ]);
        Sanctum::actingAs($ownerUser);

        $this->postJson("/api/fields/{$field->id}/slots", [
            'slots' => [[
                'date' => '2026-06-10',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'status' => 'AVAILABLE',
            ]],
        ])->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson("/api/public-fields/{$field->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Slot Sync Field')
            ->assertJsonPath('data.time_slots.0.date', '2026-06-10');
    }
}
