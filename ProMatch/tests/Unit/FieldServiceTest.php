<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Field;
use App\Models\Owner;
use App\Services\FieldService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldServiceTest extends TestCase
{
    use RefreshDatabase;

    private FieldService $fieldService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->fieldService = new FieldService();
    }

    public function test_can_get_all_fields()
    {
        $user = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $user->id, 'registration_date' => now()]);
        
        Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $fields = $this->fieldService->getAllFields();
        $this->assertCount(1, $fields);
    }
}
