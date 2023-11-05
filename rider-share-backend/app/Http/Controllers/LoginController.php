<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        //validate phone number
        $request->validate([
            'phone' => 'required|numeric',
            'email' => 'required|email'
        ]);
        //find or create a user model
        $user = User::firstOrCreate([
            'phone' => $request->phone,
            'email' => $request->email
        ]);
        if (!$user) {
            return response()->json(['message' => 'Could not process a user with that phone number.'], 401);
        }
        //send the user one time use code
        $user->notify(new LoginNeedsVerification());

        //return back a response
        return response()->json(['message' => 'Text message notification sent.']);
    }

    public function verify(Request $request)
    {
        //validate the incoming request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);
        //find the user
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();
        //is the code provided the same one saved?
        //if so, return back on auth token
        if ($user) {
            $user->update([
                'login_code' => null,
            ]);
            return $user->createToken($request->login_code)->plainTextToken;
        }
        //if not, return back a message
        return response()->json(['message' => 'Invalid verification code.', 401]);
    }
}
