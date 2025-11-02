<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'student_id' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        if ($request->has('student_id')) {
            $user->student_id = $request->student_id;
        }
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile_image) {
                Storage::delete('public/profile-images/' . $user->profile_image);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('profile-images', 'public');
            $user->profile_image = basename($avatarPath);
        }
        
        $user->save();
        
        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile_image) {
                Storage::delete('public/profile-images/' . $user->profile_image);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('profile-images', 'public');
            $user->profile_image = basename($avatarPath);
            $user->save();
        }

        return back()->with('success', 'Profile picture updated successfully!');
    }
}