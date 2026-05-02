<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\Otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'nullable|string|max:255',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'position' => $validatedData['position'],
        ]);

        $browser = Browser::browserName();
        $platform = Browser::platformName();
        $device = Browser::deviceModel();

        $token = $user->createToken($browser . '_' . $platform . '_' . $device)->plainTextToken;

        $otp = random_int(100000, 999999); // Generate a random 6-digit OTP
        Cache::put("otp_{$user->email}", $otp, now()->addMinutes(5)); // Store OTP in cache for 5 minutes

        //Send OTP via email (implement your email sending logic here)
        Mail::to($user->email)->send(new Otp($user->name, $user->email, $otp));

        // Return a response, e.g., a success message or the created user
        return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
    }

    public function verifyOtp(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validatedData = $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $cachedOtp = Cache::get("otp_{$user->email}");
        if ($cachedOtp && $validatedData['otp'] == $cachedOtp) {
            // OTP is valid, mark the user's email as verified
            $user->update(['email_verified_at' => now()]);

            // Clear the OTP from cache
            Cache::forget("otp_{$user->email}");

            return response()->json(['message' => 'OTP verified successfully. Email is now verified.']);
        } else {
            return response()->json(['message' => 'Invalid OTP. Please try again.'], 400);
        }

    }

    public function resendOtp(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $otp = random_int(100000, 999999); // Generate a random 6-digit OTP
        Cache::put("otp_{$user->email}", $otp, now()->addMinutes(5)); // Store OTP in cache for 5 minutes

        //Send OTP via email (implement your email sending logic here)
        Mail::to($user->email)->send(new Otp($user->name, $user->email, $otp));

        return response()->json(['message' => 'OTP sent successfully. Please check your email.']);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->tokens()->delete(); // Revoke all tokens for the user

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validatedData)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Email not verified. Please verify your email before logging in.'], 403);
        }

        $browser = Browser::browserName();
        $platform = Browser::platformName();
        $device = Browser::deviceModel();

        $token = $user->createToken($browser . '_' . $platform . '_' . $device)->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token]);
    }
}
