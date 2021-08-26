<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Http\Resources\v1\UserResource;

class UserController extends Controller
{
    public function getUserInfo(Request $request) {
        return response()->json(new UserResource(auth()->user()), 200);
    }

    public function getAllStudents(Request $request) {
        $students = User::role('student')->get();
        return response()->json(UserResource::collection($students), 200);
    }

    public function getAllInstructors(Request $request) {
        $instructor = User::role('instructor')->get();
        return response()->json(UserResource::collection($instructor), 200);
    }

    // admin registers new instructor/student
    public function register(Request $request) {
        if (! auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'role' => 'required|string'
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
                'password' => $password
            ],
        ], 201);
    }
}
