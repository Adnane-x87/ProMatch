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
}
