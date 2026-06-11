<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        // Web form route POST /register sends a combined 'name' field.
        // API route POST /api/register sends 'first_name' and 'last_name' separately.
        // The old check $request->is('register') was DEAD CODE because the route is
        // /api/register (path = "api/register"), so it never matched. Fixed below.
        if ($request->is('register')) {
            // Web form validation (POST /register, no /api prefix)
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'phone' => ['nullable', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\s().-]+$/'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
                'terms' => ['accepted'],
            ], [
                'name.required' => 'Le nom complet est obligatoire.',
                'name.min' => 'Le nom complet doit contenir au moins 3 caracteres.',
                'phone.min' => 'Le telephone doit contenir au moins 8 caracteres.',
                'phone.regex' => 'Veuillez saisir un numero de telephone valide.',
                'email.required' => 'L email est obligatoire.',
                'email.email' => 'Veuillez saisir une adresse email valide.',
                'email.unique' => 'Cette adresse email est deja utilisee.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caracteres.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'terms.accepted' => 'Vous devez accepter les conditions generales.',
            ]);
        } elseif ($request->expectsJson() || $request->is('api/*')) {
            // API / mobile validation (POST /api/register)
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone'      => ['nullable', 'string', 'min:8', 'max:30'],
                'password'   => ['required', 'string', 'min:6'],
                'role'       => ['nullable', Rule::in(['tenant', 'owner', 'employee'])],
                'type'       => ['nullable', Rule::in(['tenant', 'owner', 'employee'])],
            ], [
                'first_name.required' => 'Le prénom est requis.',
                'last_name.required'  => 'Le nom de famille est requis.',
                'email.required'      => 'L\'email est requis.',
                'email.unique'        => 'Cette adresse email est déjà utilisée.',
                'password.required'   => 'Le mot de passe est requis.',
                'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]);
        }

        $payload = $request->all();
        $payload['role'] = $payload['role'] ?? $payload['type'] ?? 'tenant';

        validator($payload, [
            'role' => ['nullable', Rule::in(['tenant', 'owner', 'employee'])],
            'type' => ['nullable', Rule::in(['tenant', 'owner', 'employee'])],
        ])->validate();

        $user = $this->userService->register($payload)->load(['tenant', 'owner', 'employee', 'roles']);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
                'errors' => [],
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Eager-load relations to supply roles and attributes to the client
        $user->load(['tenant', 'owner', 'employee', 'roles', 'permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Connexion reussie',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'data' => null,
        ]);
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function block($id)
    {
        $user = User::findOrFail($id);
        $owner = Owner::first();

        if ($owner) {
            $owner->blockUser($user);
        } else {
            $user->is_blocked = true;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur bloque avec succes.',
            'data' => $user->fresh(),
        ]);
    }

    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $owner = Owner::first();

        if ($owner) {
            $owner->unblockUser($user);
        } else {
            $user->is_blocked = false;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur debloque avec succes.',
            'data' => $user->fresh(),
        ]);
    }
}
