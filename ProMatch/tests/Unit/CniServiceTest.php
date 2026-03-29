<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Tenant;
use App\Services\CniService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_cni@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c1', 'birth_date' => '2000-01-01', 'is_cni_valid' => false, 'cni_image' => 'fake_url']);

        $this->cniService->verifyCNI($tenant->id, true);
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'is_cni_valid' => 1]);

        $this->cniService->verifyCNI($tenant->id, false);
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'is_cni_valid' => 0, 'cni_image' => null]);
    }
}
