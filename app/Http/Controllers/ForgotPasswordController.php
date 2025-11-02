<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\PHPMailerService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
            }

            // Generate reset token
            $token = Str::random(60);
            
            $user->update([
                'reset_token' => $token,
                'reset_token_expires' => now()->addHours(1)
            ]);

            // Send reset email using PHPMailerService
            $mailer = new PHPMailerService();
            $resetUrl = URL::temporarySignedRoute(
                'password.reset',
                now()->addHours(1),
                ['token' => $token, 'email' => $user->email]
            );

            $result = $mailer->sendPasswordResetEmail($user, $resetUrl);

            DB::commit();

            if ($result) {
                return back()->with('status', 'Password reset link has been sent to your email!');
            }

            return back()->withErrors(['email' => 'Failed to send reset email. Please try again.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)
                        ->where('reset_token', $request->token)
                        ->where('reset_token_expires', '>', now())
                        ->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Invalid or expired reset token.']);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_token' => null,
                'reset_token_expires' => null
            ]);

            DB::commit();

            return redirect('/login')->with('status', 'Password has been reset successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        }
    }
}