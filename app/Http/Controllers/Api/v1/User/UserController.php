<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group User
 *
 * API endpoints for creating user and getting user info
 */
class UserController extends Controller
{
    /**
     * Get logged in user's info
     *
     * @authenticated
     * @param Request $request
     * @return UserResource
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getUserInfo(Request $request)
    {
        return new UserResource(auth()->user());
    }

    /**
     * Get all students' info
     *
     * @authenticated
     * @param Request $request
     * @return UserResource
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getAllStudents(Request $request)
    {
        $students = User::role('student')->get();
        return UserResource::collection($students);
    }

    /**
     * Get all instructors' info
     *
     * @authenticated
     * @param Request $request
     * @return UserResource
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getAllInstructors(Request $request)
    {
        $instructor = User::role('instructor')->get();
        return UserResource::collection($instructor);
    }

    /**
     * Register a new user
     *
     * @authenticated
     * @param Request $request
     * @bodyParam username string required A unique username
     * @bodyParam email string required A unique email address
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam gender string required
     * @bodyParam date_of_birth string required Example: 2020-12-01
     * @bodyParam role string required The role of the user; options: student, instructor, admin
     *
     * @return Illuminate\Http\JsonResponse
     * @response 201 {
     *  "message": "Successfully created user!",
     *  "credentials": {
     *      "email": "user@test.com",
     *      "password": "12345678"
     *  }
     * }
     */
    public function registerUser(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'role' => 'required|string',
        ]);

        // $password = Str::random(6);
        $password = '12345678';

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Successfully created user!',
            'credentials' => [
                'email' => $user->email,
                'password' => $password,
            ],
        ], 201);
    }
}
