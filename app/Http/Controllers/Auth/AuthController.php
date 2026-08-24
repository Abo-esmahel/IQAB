<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'role' => 'user',
        ]);

        app(WalletService::class)->getOrCreateWallet($user);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been suspended.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showProfile(Request $request)
    {
        return view('auth.profile', ['user' => $request->user()]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());

        return back()->with('success', 'Profile updated successfully!');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
