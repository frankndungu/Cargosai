<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Import the Log facade for logging errors
use App\Models\EmailVerification; // Model for email verification tokens
use App\Mail\ConfirmEmail; // Mailable class for sending confirmation emails

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            // Create token for the user
            $token = $user->createToken('authToken')->plainTextToken;

            // Automatically send email confirmation
            $this->sendConfirmationEmail($user);

            return response()->json(['token' => $token, 'user' => $user], 201);
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json(['error' => 'Registration failed. Please try again later.'], 500);
        }
    }

    // Log in a user
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 200);
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return response()->json(['error' => 'Login failed. Please try again later.'], 500);
        }
    }

    // Log out the user
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json(['error' => 'Logout failed. Please try again later.'], 500);
        }
    }

    // Send confirmation email
    public function sendConfirmationEmail($user)
    {
        try {
            $token = Str::random(32);

            // Save or update the verification token in the database
            EmailVerification::updateOrCreate(
                ['user_id' => $user->id], 
                ['token' => $token]
            );

            // Send email
            Mail::to($user->email)->send(new ConfirmEmail($token));
        } catch (\Exception $e) {
            Log::error('Error sending confirmation email: ' . $e->getMessage());
            throw $e; // Rethrow to handle it in the controller
        }
    }

    // Confirm email
    public function confirmEmail($token)
    {
        try {
            $verification = EmailVerification::where('token', $token)->first();

            if (!$verification) {
                return response()->json(['message' => 'Invalid or expired token.'], 400);
            }

            $user = $verification->user;
            $user->email_verified_at = now();
            $user->save();

            // Delete the token after successful confirmation
            $verification->delete();

            return response()->json(['message' => 'Email confirmed successfully.']);
        } catch (\Exception $e) {
            Log::error('Error confirming email: ' . $e->getMessage());
            return response()->json(['message' => 'Email confirmation failed. Please try again later.'], 500);
        }
    }

    // Resend email confirmation
    public function resendEmailVerification(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
        
            $user = User::where('email', $request->email)->first();
        
            Log::info('Resending verification email for user: ' . $user->email);
        
            if ($user->email_verified_at) {
                return response()->json(['message' => 'Email already verified.'], 400);
            }
        
            Log::info('Starting email send process...');
            
            try {
                $this->sendConfirmationEmail($user);
                Log::info('Email sent successfully');
            } catch (\Exception $e) {
                Log::error('Mail sending failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                throw $e;
            }
        
            return response()->json(['message' => 'Verification email resent.']);
        } catch (\Exception $e) {
            Log::error('Error resending verification email: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to resend the verification email. Please try again later.',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
}
