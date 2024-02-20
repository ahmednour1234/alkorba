<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class GoogleLoginController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $existingUser = User::where('email', $user->email)->first();

            if ($existingUser) {
                // If the user already exists, log them in
                Auth::login($existingUser);
                $token = $existingUser->createToken('auth-token')->plainTextToken;
                return redirect('http://localhost:8080/home?token=' . $token);
                return response()->json([
                    'message' => 'Login Successful',
                    'token' => $token,
                    'user' => $existingUser,
                ], 200);
            } else {
                // If the user doesn't exist, create a new user
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make(str::random(8)),
                    // Add other necessary fields
                ]);

                // Log in the new user
                Auth::login($newUser);

                // Generate a new token for the new user
                $token = $newUser->createToken('auth-token')->plainTextToken;
                return redirect('http://localhost:8080/home?token=' . $token);
                return response()->json([
                    'message' => 'Registration Successful',
                    'token' => $token,
                    'user' => $newUser,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();

            $existingUser = User::where('email', $user->email)->first();

            if ($existingUser) {
                // If the user already exists, log them in
                Auth::login($existingUser);
                $token = $existingUser->createToken('auth-token')->plainTextToken;
                return redirect('http://localhost:8080/home?token=' . $token);
                return response()->json([
                    'message' => 'Login Successful',
                    'token' => $token,
                    'user' => $existingUser,
                ], 200);
            } else {
                // If the user doesn't exist, create a new user
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make(str::random(8)),
                    // Add other necessary fields
                ]);

                // Log in the new user
                Auth::login($newUser);

                // Generate a new token for the new user
                $token = $newUser->createToken('auth-token')->plainTextToken;
                return redirect('http://localhost:8080/home?token=' . $token);
                return response()->json([
                    'message' => 'Registration Successful',
                    'token' => $token,
                    'user' => $newUser,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

}
