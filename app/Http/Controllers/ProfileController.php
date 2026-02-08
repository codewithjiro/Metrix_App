<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Basic Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Handle Password Change Logic
        if ($request->filled('new_password')) {
            // Verify current password matches database
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current records.']);
            }
            
            $user->password = Hash::make($request->new_password);
        }

        // 3. Handle Profile Photo Upload
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists and isn't a default
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new photo
            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // 4. Update Basic Info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}