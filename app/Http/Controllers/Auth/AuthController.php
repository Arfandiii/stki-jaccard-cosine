<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|email:dns',
            'password' => 'required'
        ]);

        // mapping karena inputan name-nya "username"
        $credentials = [
            'email'    => $credentials['username'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // kalau client minta JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'message'  => 'Login berhasil',
                    'redirect' => route('admin.dashboard')
                ]);
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        // gagal
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }
        return back()->with('loginError', 'Email atau password salah.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:dns', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
