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
        $data = $this->userService->login($request->all());
        
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request->user());
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}