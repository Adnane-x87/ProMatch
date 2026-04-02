<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Owner;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Services\PublicFieldService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFieldServiceTest extends TestCase
{
    use RefreshDatabase;

    private PublicFieldService $fieldService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->fieldService = new PublicFieldService();
    }

    public function test_can_search_fields()
    {
        $u = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $o = Owner::create(['user_id' => $u->id, 'registration_date' => now()]);

        Field::create(['owner_id' => $o->id, 'name' => 'Stade de France', 'address' => 'Saint-Denis', 'price_per_hour' => 100]);
        Field::create(['owner_id' => $o->id, 'name' => 'Parc des Princes', 'address' => 'Paris', 'price_per_hour' => 120]);

        $results = $this->fieldService->searchFields('France');
        $this->assertCount(1, $results);
        $this->assertEquals('Stade de France', $results->first()->name);

        $results = $this->fieldService->searchFields('Paris');
        $this->assertCount(1, $results);
        $this->assertEquals('Parc des Princes', $results->first()->name);
    }

    public function test_can_get_field_details()
    {
        $u = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o2@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $o = Owner::create(['user_id' => $u->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $o->id, 'name' => 'Field 1', 'address' => 'Address 1', 'price_per_hour' => 50]);
        
        $details = $this->fieldService->getFieldDetails($field->id);
        $this->assertEquals('Field 1', $details->name);
    }

    public function test_can_get_available_slots()
    {
        $u = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o3@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $o = Owner::create(['user_id' => $u->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $o->id, 'name' => 'F', 'address' => 'A', 'price_per_hour' => 50]);
        TimeSlot::create([
            'field_id' => $field->id,
            'date' => '2024-06-01',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'AVAILABLE'
        ]);
        TimeSlot::create([
            'field_id' => $field->id,
            'date' => '2024-06-01',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'status' => 'RESERVED'
        ]);

        $slots = $this->fieldService->getAvailableSlots($field->id, '2024-06-01');
        $this->assertCount(1, $slots);
    }
}
