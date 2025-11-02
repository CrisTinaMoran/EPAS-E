<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PrivateLoginController extends Controller
{
    public function showLoginForm()
    {
        // If user is already authenticated, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToRoleDashboard();
        }
        
        return view('private.private-login');
    }

    public function login(Request $request)
    {
        // Add rate limiting (5 attempts per minute)
        $key = 'private-login:' . $request->ip();
        $maxAttempts = 5;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.',
            ]);
        }
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Check if user is admin or teacher
            if (!in_array(Auth::user()->role, ['admin', 'teacher'])) {
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
            
            return redirect()->intended('/admin/dashboard');
        }

        // Increment login attempts
        RateLimiter::hit($key, $decaySeconds);
        
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    private function redirectToRoleDashboard()
    {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'teacher'])) {
            return redirect('/admin/dashboard');
        }
        return redirect('/student/dashboard');
    }
}