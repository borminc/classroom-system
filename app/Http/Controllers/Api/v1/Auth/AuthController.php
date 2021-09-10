<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Requests\v1\Auth\SelfRegisterRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * API endpoints for authentication such as login and registration
 */
class AuthController extends Controller
{
    /**
     * Self-registration of new users
     *
     * @param SelfRegisterRequest $request
     * @bodyParam username string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam gender string required
     * @bodyParam date_of_birth string required Date in the format of Y-m-d
     *
     * @return Illuminate\Http\JsonResponse
     * @response 201 {
     *  "message": "Successfully created user!"
     * }
     */
    public function selfRegisterAsStudent(SelfRegisterRequest $request)
    {
        $user = User::create(
            $request->except('password') + ['password' => Hash::make($request->password)],
        );
        $user->assignRole('student');

        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam remember_me boolean required
     *
     * @return Illuminate\Http\JsonResponse
     * @response 200 {
     *  "access_token": "eyJ0eX...",
     *  "token_type": "Bearer",
     *  "expires_at": "2021-09-06 15:02:06",
     *  "user": {
     *      "id": 2,
     *      "username": "i_1",
     *      "full_name": "Instructor 1",
     *      "email": "i_1@test.com",
     *      "gender": "male",
     *      "date_of_birth": "2000-02-03 14:23:35"
     *  },
     *  "verified": true
     * }
     * @response 401 {
     *  "message": "Invalid username or password."
     * }
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid username or password.',
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => new UserResource($user),
            'verified' => !($user->email_verified_at == null),
        ], 200);
    }

    /**
     * Log out user
     *
     * @authenticated
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     * @response {
     *  "message": "Successfully logged out"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
