<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Api\ApiController;
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
class UserController extends ApiController
{
    /**
     * Get a list of all users
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return $this->okWithData(UserResource::collection(User::all()));
    }

    /**
     * Get the logged in user's info
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getLoggedInUserInfo()
    {
        return $this->okWithData(new UserResource(auth()->user()));
    }

    /**
     * Get all students' info
     *
     * @authenticated
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getAllStudents(Request $request)
    {
        $students = User::role('student')->get();
        return $this->okWithData(UserResource::collection($students));
    }

    /**
     * Get all instructors' info
     *
     * @authenticated
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function getAllInstructors(Request $request)
    {
        $instructor = User::role('instructor')->get();
        return $this->okWithData(UserResource::collection($instructor));
    }

    /**
     * Create a new user
     *
     * @authenticated
     * @param StoreUserRequest $request
     * @return Illuminate\Http\JsonResponse
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

        return $this->created([
            'credentials' => [
                'email' => $user->email,
                'password' => $password,
            ],
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Get the specified user.
     *
     * @authenticated
     * @param  \App\Models\User  $user
     * @return Illuminate\Http\JsonResponse
     * @apiResource App\Http\Resources\v1\UserResource
     * @apiResourceModel App\Models\User
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return $this->okWithData(new UserResource($user));
    }

    /**
     * Update the specified user
     *
     * @authenticated
     * @param  UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->except('role_ids'));
        $user->syncRoles($request->role_ids);
        return $this->updated(new UserResource($user));
    }

    /**
     * Delete the specified user
     *
     * @authenticated
     * @param  \App\Models\User  $user
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return $this->deleted(new UserResource($user));
    }
}
