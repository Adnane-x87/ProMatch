<?php

namespace Tests\Unit;

use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Tenant;
use App\Services\CniService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CniServiceTest extends TestCase
{
    use RefreshDatabase;

    private CniService $cniService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->cniService = new CniService();
    }

    public function test_can_verify_cni()
    {
        Storage::fake('public');

        $ownerUser = User::create(['first_name' => 'Owner', 'last_name' => 'User', 'email' => 'owner_cni@t.com', 'password' => '1', 'phone' => '9', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now()]);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser1 = User::create(['first_name' => 'T1', 'last_name' => 'U1', 'email' => 't1_cni@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant1 = Tenant::create(['user_id' => $tenantUser1->id, 'cin' => 'c1', 'is_cni_valid' => false]);

        $tenantUser2 = User::create(['first_name' => 'T2', 'last_name' => 'U2', 'email' => 't2_cni@t.com', 'password' => '1', 'phone' => '2', 'type' => 'tenant']);
        $tenant2 = Tenant::create(['user_id' => $tenantUser2->id, 'cin' => 'c2', 'is_cni_valid' => false]);

        $reservationToApprove = Reservation::create([
            'tenant_id' => $tenant1->id,
            'field_id' => $field->id,
            'first_name' => 'T1',
            'last_name' => 'U1',
            'email' => 't1_cni@t.com',
            'phone' => '1',
            'request_date' => now()->toDateString(),
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'price' => 100,
            'cni_image' => 'reservations/cnis/approve.png',
            'status' => 'PENDING',
        ]);

        $reservationToReject = Reservation::create([
            'tenant_id' => $tenant2->id,
            'field_id' => $field->id,
            'first_name' => 'T2',
            'last_name' => 'U2',
            'email' => 't2_cni@t.com',
            'phone' => '2',
            'request_date' => now()->toDateString(),
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'price' => 100,
            'cni_image' => 'reservations/cnis/reject.png',
            'status' => 'PENDING',
        ]);

        $this->assertCount(2, $this->cniService->getPendingCNIs());

        $this->cniService->verifyCNI($reservationToApprove->id, true);
        $this->assertDatabaseHas('tenants', ['id' => $tenant1->id, 'is_cni_valid' => 1, 'cni_image' => 'reservations/cnis/approve.png']);
        $this->assertDatabaseHas('reservations', ['id' => $reservationToApprove->id, 'status' => 'APPROVED']);

        $this->cniService->verifyCNI($reservationToReject->id, false);
        $this->assertDatabaseHas('tenants', ['id' => $tenant2->id, 'is_cni_valid' => 0, 'cni_image' => null]);
        $this->assertDatabaseHas('reservations', ['id' => $reservationToReject->id, 'status' => 'REJECTED', 'cni_image' => null]);
    }
}
