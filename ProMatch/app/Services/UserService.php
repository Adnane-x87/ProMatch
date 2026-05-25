<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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
            'type'       => 'nullable|string|in:owner,tenant,employee',
        ])->validate();

        // Default type to 'tenant' if not provided
        $validated['type'] = $validated['type'] ?? 'tenant';
        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        // Create the associated model depending on the type
        if ($user->type === 'tenant') {
            $user->tenant()->create([
                'cin' => null,
                'cni_image' => null,
                'is_cni_valid' => false
            ]);
        } elseif ($user->type === 'owner') {
            $user->owner()->create([
                'registration_date' => now()
            ]);
        } elseif ($user->type === 'employee') {
            $user->employee()->create();
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
