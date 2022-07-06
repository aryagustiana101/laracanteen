<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login()
    {
        return view('auth.login', [
            'tabTitle' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'user_credential' => ['required', 'max:255'],
            'password' => ['required', 'max:255'],
        ]);
        $user = User::auth(['admin_auth' => $validated['user_credential']])->first();
        if ($user) {
            if (Hash::check($validated['password'], $user->password)) {
                Auth::loginUsingId($user->id);
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
            return back()->with(['error' => 'Password salah']);
        }
        return back()->with(['error' => 'Pengguna tidak ditemukan.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
