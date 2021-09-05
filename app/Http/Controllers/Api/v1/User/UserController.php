<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * @group Users
 *
 * API endpoints for creating user and getting user info
 */
class UserController extends Controller
{
    /**
     * Get a list of all users
     *
     * @authenticated
     * @return \Illuminate\Http\Response
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection(User::all());
    }

    /**
     * Get the logged in user's info
     *
     * @authenticated
     * @return App\Http\Resources\v1\UserResource
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getLoggedInUserInfo()
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
     * Create a new user
     *
     * @authenticated
     * @param Request $request
     * @bodyParam username string required A unique username
     * @bodyParam email string required A unique email address
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam gender string required
     * @bodyParam date_of_birth string required Example: 2020-12-01
     * @bodyParam role_ids int[] required The role ids of the user
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
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|distinct',
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

        foreach ($request->role_ids as $role_id) {
            $role = Role::findOrFail($role_id);
            $user->assignRole($role);
        }

        return response()->json([
            'message' => 'Successfully created user!',
            'credentials' => [
                'email' => $user->email,
                'password' => $password,
            ],
        ], 201);

    }

    /**
     * Get the specified user.
     *
     * @authenticated
     * @param  \App\Models\User  $user
     * @urlParam id integer required The ID of the user
     *
     * @return \Illuminate\Http\Response
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update the specified user
     *
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @urlParam id integer required The ID of the user
     * @bodyParam username string required A unique username
     * @bodyParam email string required A unique email address
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam gender string required
     * @bodyParam date_of_birth string required Example: 2020-12-01
     * @bodyParam role_ids int[] required The role ids of the user
     *
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully updated user!"
     * }
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'username' => 'required|string',
            'email' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|distinct',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->save();
        $user->syncRoles($request->role_ids);

        return response()->json([
            "message" => "Successfully updated user!",
        ], 200);
    }

    /**
     * Delete the specified user
     *
     * @authenticated
     * @param  \App\Models\User  $user
     * @urlParam id integer required The ID of the user
     *
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully deleted user!"
     * }
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        return response()->json([
            "message" => "Successfully deleted user!",
        ], 200);
    }
}
