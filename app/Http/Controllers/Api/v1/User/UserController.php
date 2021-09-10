<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\StoreUserRequest;
use App\Http\Requests\v1\User\UpdateUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
     * @param StoreUserRequest $request
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
    public function store(StoreUserRequest $request)
    {
        // $password = Str::random(6);
        $password = '12345678';

        $user = User::create(
            $request->except('password') + ['password' => Hash::make($password)]
        );

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
     * @param  UpdateUserRequest  $request
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
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->except('role_ids'));
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
