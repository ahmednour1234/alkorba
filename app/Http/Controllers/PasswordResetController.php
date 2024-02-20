<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\MobilePasswordNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $verificationCode = Str::random(6); // Generate a random verification code

        // Store the verification code in the user model or any appropriate storage
        $user->verification_code = $verificationCode;
        $user->save();

        // Send the email notification with the verification code
        Notification::send($user, new ResetPasswordNotification($verificationCode));

        return response()->json(['message' => 'Verification code sent successfully']);
    }

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('verification_code', $request->verification_code)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid verification code'], 422);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->verification_code = null; // Reset the verification code
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
    public function sendSmsForPasswordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required'], // Adjust the regex based on your phone number format
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $verificationCode = Str::random(6);

        // Store the verification code in the user model or any appropriate storage
        $user->verification_code = $verificationCode;
        $user->save();

        // Send SMS using notification
        Notification::send($user, new MobilePasswordNotification($verificationCode));
        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key' => config('services.vonage.key'),
            'api_secret' => config('services.vonage.secret'),
            // Other SMS parameters...
        ]);
        return response()->json(['message' => 'Verification code sent successfully',$response]);
    }
}
