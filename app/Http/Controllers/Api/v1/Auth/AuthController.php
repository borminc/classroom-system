<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\ApiController;
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
class AuthController extends ApiController
{
    /**
     * Self-registration of new users
     *
     * @param SelfRegisterRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function selfRegisterAsStudent(SelfRegisterRequest $request)
    {
        $user = User::create(
            $request->except('password') + ['password' => Hash::make($request->password)],
        );
        $user->assignRole('student');

        return $this->created(new UserResource($user));
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return $this->unauthenticated('Invalid username or password');
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return $this->okWithData([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => new UserResource($user),
            'verified' => !($user->email_verified_at == null),
        ], 'Login successfully');
    }

    /**
     * Log out user
     *
     * @authenticated
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->okWithMessage('Successfully logged out');
    }
}
