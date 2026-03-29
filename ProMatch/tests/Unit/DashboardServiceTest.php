<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private DashboardService $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->dashboardService = new DashboardService();
    }

    public function test_can_get_dashboard_stats()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now(), 'siret' => 's5']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser1 = User::create(['first_name' => 'T1', 'last_name' => 'U1', 'email' => 't1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        Tenant::create(['user_id' => $tenantUser1->id, 'cin' => 'cin1', 'birth_date' => '2000-01-01', 'is_cni_valid' => true, 'cni_image' => 'url1']);

        $tenantUser2 = User::create(['first_name' => 'T2', 'last_name' => 'U2', 'email' => 't2@t.com', 'password' => '1', 'phone' => '2', 'type' => 'tenant']);
        Tenant::create(['user_id' => $tenantUser2->id, 'cin' => 'cin2', 'birth_date' => '2000-01-01', 'is_cni_valid' => false, 'cni_image' => 'url2']);

        Reservation::create([
            'tenant_id' => $tenantUser1->id,
            'field_id' => $field->id,
            'first_name' => 'T',
            'last_name' => '1',
            'email' => 't@1.com',
            'phone' => '1',
            'request_date' => now()->toDateString(),
            'start_time' => now()->subHour(),
            'end_time' => now(),
            'price' => 200,
            'status' => 'APPROVED'
        ]);

        $stats = $this->dashboardService->getDashboardStats();

        $this->assertEquals(2, $stats['total_clients']);
        $this->assertEquals(1, $stats['validated_cnis']);
        $this->assertEquals(1, $stats['pending_cnis']);
        $this->assertEquals(200, $stats['todays_income']);
    }
}
