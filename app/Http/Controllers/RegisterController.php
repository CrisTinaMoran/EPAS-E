<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Services\PHPMailerService;
use App\Http\Controllers\AnnouncementController;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'ext_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_id' => 'required|string|max:20|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'terms' => 'required|accepted',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'terms.required' => 'You must accept the Terms and Conditions and Privacy Policy.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'department_id' => 1,
            'stat' => 0,
            'email_verified_at' => null,
        ]);

        $content = "New student registered: {$user->first_name} {$user->last_name} with student: {$user->student_id}. Account is pending approval and email verification.";
        AnnouncementController::createAutomaticAnnouncement(
            'user_registered', 
            $content, 
            $user, 
            'admin,instructor' // Only admins and instructors see new registrations
        );

        // Use your custom PHPMailerService
        $mailer = new PHPMailerService();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Send verification email using your custom service
        $result = $mailer->sendVerificationEmail($user, $verificationUrl);

        if (!$result) {
            return redirect()->back()
                ->withErrors(['email' => 'Failed to send verification email. Please try again.'])
                ->withInput();
        }

        return redirect()->route('login')
            ->with('status', 'Registration submitted successfully! Please check your email for verification. Your account is pending admin approval.');
    }
}