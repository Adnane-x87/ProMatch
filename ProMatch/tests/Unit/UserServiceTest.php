<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->userService = new UserService();
    }

    public function test_can_get_all_users()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
            'type' => 'employee'
        ]);

        $users = $this->userService->getAllUsers();

        $this->assertCount(1, $users);
    }

    public function test_can_delete_user()
    {
        $user = User::create([
            'first_name' => 'To Delete',
            'last_name' => 'User',
            'email' => 'delete@test.com',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
            'type' => 'tenant'
        ]);

        $result = $this->userService->deleteUser($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_owner_can_block_and_unblock_user()
    {
        $ownerUser = User::create([
            'first_name' => 'Owner',
            'last_name' => 'User',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'phone' => '1112223333',
            'type' => 'owner'
        ]);

        $owner = \App\Models\Owner::create([
            'user_id' => $ownerUser->id,
            'registration_date' => now()->toDateString()
        ]);

        $tenantUser = User::create([
            'first_name' => 'Tenant',
            'last_name' => 'User',
            'email' => 'tenant@test.com',
            'password' => bcrypt('password'),
            'phone' => '4445556666',
            'type' => 'tenant'
        ]);

        // Default should be false
        $this->assertFalse($tenantUser->is_blocked);

        // Block user
        $resultBlock = $owner->blockUser($tenantUser);
        $this->assertTrue($resultBlock);
        $this->assertTrue($tenantUser->fresh()->is_blocked);

        // Unblock user
        $resultUnblock = $owner->unblockUser($tenantUser);
        $this->assertTrue($resultUnblock);
        $this->assertFalse($tenantUser->fresh()->is_blocked);
    }
}
