<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Registration (UPDATED WITH IMAGE UPLOAD)
    public function register(Request $request)
    {
        // 1. Validation including the image
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Allow image upload, max 2MB (2048 KB)
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $path = null;

        // 2. Handle the file upload if it exists
        if ($request->hasFile('profile_photo')) {
            // Save to 'storage/app/public/profile_photos' and get the path
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // 3. Create User with the photo path
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0, // Default to Customer
            'profile_photo' => $path, // Save the path (or null) to database
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    // Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}