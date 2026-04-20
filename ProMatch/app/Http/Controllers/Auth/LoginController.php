<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle a web login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Force remember false since users table doesn't have remember_token
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Rediriger uniquement Adnane (owner) vers le dashboard
            if ($user->type === 'owner' || strtolower($user->first_name) === 'adnane') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Ces informations ne correspondent à aucun compte.'])
            ->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'));
    }
}
