<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Http\Resources\v1\UserResource;

class AuthController extends Controller
{
    // for self-registration
    // public function register(Request $request) {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:8|confirmed'
    //     ]);

    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password)
    //     ]);

    //     return response()->json([
    //         'message' => 'Successfully created user!'
    //     ], 201);
    // }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $creds = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($creds)) {
            return response()->json([
                'message' => 'Invalid username or password.'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => new UserResource($user),
            'verified' => !($user->email_verified_at == null)
        ];        
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
