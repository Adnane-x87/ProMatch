<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $data = $this->userService->register($request->all());
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides'
            ], 401);
        }

        // If using Sanctum, we could create a token here:
        $token = $user->createToken('auth_token')->plainTextToken;

        // Eager-load relations to supply roles and attributes to the client
        $user->load(['tenant', 'owner', 'employee']);

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request->user());
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function block($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $owner = \App\Models\Owner::first(); // Current admin/owner
        
        if ($owner) {
            $owner->blockUser($user);
        } else {
            $user->is_blocked = true;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur bloqué avec succès.',
            'data' => $user->fresh()
        ]);
    }

    public function unblock($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $owner = \App\Models\Owner::first(); // Current admin/owner
        
        if ($owner) {
            $owner->unblockUser($user);
        } else {
            $user->is_blocked = false;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur débloqué avec succès.',
            'data' => $user->fresh()
        ]);
    }
}