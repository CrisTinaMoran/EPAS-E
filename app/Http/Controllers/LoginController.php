<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect('/student/dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login:' . $request->ip();
        $maxAttempts = 5;
        $decaySeconds = 1800 ;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 1800 ) . ' minutes.',
            ]);
        }
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Check if user is a student
            if (Auth::user()->role !== 'student') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'An Error Occurred please approach the administrator.',
                ]);
            }
            
            // Check if user is approved
            if (Auth::user()->stat == 0) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval. Please contact administrator.',
                ]);
            }
            
            RateLimiter::clear($key);
            
            $user = Auth::user();
            $user->last_login = now();
            $user->save();
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            return redirect()->intended('/student/dashboard');
        }

        // Increment login attempts
        RateLimiter::hit($key, $decaySeconds);
        
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear all session data
        session()->flush();

        return redirect('/login')->with('status', 'You have been logged out successfully.');
    }
}