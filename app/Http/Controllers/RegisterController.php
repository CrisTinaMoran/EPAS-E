<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // validation rules with strong password policy
        $rules = [
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'ext_name'        => 'nullable|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users,email',
            // Password rules: min 8, mixed case, numbers, symbols, and check against HIBP
            'password'        => ['required','confirmed', Password::min(8)
                                        ->mixedCase()
                                        ->numbers()
                                        ->symbols()
                                        ->uncompromised()],
        ];

        // optional custom messages (helps clarify password requirement)
        $messages = [
            'password.required' => 'Please provide a password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.unique' => 'That email is already registered.',
            // fallback generic message for password complexity
            'password' => 'Password must be at least 8 characters and include uppercase, lowercase, numbers, and a special character.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // get only validated data
        $data = $validator->validated();

        // normalize email
        $data['email'] = strtolower($data['email']);

        // create user inside a transaction for safety
        DB::beginTransaction();

        try {
            $user = User::create([
                'first_name'    => $data['first_name'],
                'middle_name'   => $data['middle_name'] ?? null,
                'last_name'     => $data['last_name'],
                'ext_name'      => $data['ext_name'] ?? null,
                'email'         => $data['email'],
                'password'      => Hash::make($data['password']),
                'role'          => 'User', // Default role for new registrations
                'department_id' => $request->department_id ?? 1, // default if not provided
                'stat'          => 0, // inactive until admin approves
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // Log the exception in real app (omitted for brevity)
            return redirect()->back()
                ->withInput()
                ->with('status', 'An error occurred while creating your account. Please try again later.');
        }

        return redirect()->route('register')
            ->with('status', 'Registration submitted successfully! Your account is pending admin approval. You will receive an email once your account is activated.');
    }
}
