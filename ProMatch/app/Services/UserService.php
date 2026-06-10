<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Delete a user by ID.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        $user = User::findOrFail($userId);
        return $user->delete();
    }

    /**
     * Register a new user and create the appropriate associated model.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        // Parse 'name' into 'first_name' and 'last_name' if necessary
        if (!isset($data['first_name']) && isset($data['name'])) {
            $parts = explode(' ', trim($data['name']), 2);
            $data['first_name'] = $parts[0] ?? '';
            $data['last_name'] = $parts[1] ?? '';
        }

        // Validate basic inputs
        $validated = validator($data, [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
            'phone'      => 'nullable|string',
            'role'       => 'nullable|string|in:owner,tenant,employee',
            'position'   => 'nullable|string',
            'hire_date'  => 'nullable|date',
        ])->validate();

        $role = $validated['role'] ?? 'tenant';
        $position = $validated['position'] ?? 'Staff';
        $hireDate = $validated['hire_date'] ?? now();

        unset($validated['role']);
        unset($validated['position'], $validated['hire_date']);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        $roleModel = Role::findOrCreate($role, 'web');
        $user->assignRole($roleModel);

        // Create the associated model depending on the role.
        if ($user->hasRole('tenant')) {
            $user->tenant()->create([
                'cni_image' => null,
                'is_cni_valid' => false
            ]);
        } elseif ($user->hasRole('owner')) {
            $user->owner()->create([
                'registration_date' => now()
            ]);
        } elseif ($user->hasRole('employee')) {
            $user->employee()->create([
                'position' => $position,
                'hire_date' => $hireDate,
            ]);
        }

        return $user;
    }

    /**
     * Log out a user by deleting their personal access tokens.
     *
     * @param User|null $user
     * @return void
     */
    public function logout(?User $user): void
    {
        if ($user) {
            $user->tokens()->delete();
        }
    }
}
