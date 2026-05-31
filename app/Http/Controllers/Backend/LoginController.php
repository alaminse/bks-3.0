<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('backend.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                $request->session()->regenerate();
                return redirect()
                ->route('backend.dashboard')
                ->with('success', 'Login successfully');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'You are not authorized to access admin panel.',
            ]);
        }

        return redirect()->route('backend.login')->withErrors([
            'email' => 'The provided ADMIN credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('backend.login');
    }
}
