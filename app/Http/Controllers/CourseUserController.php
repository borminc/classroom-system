<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Http\Resources\CourseResource;

class CourseUserController extends Controller
{
    // admin registers student to course
    public function registerStudent(Request $request) {
        if (! auth()->user()->hasRole('admin')) {
            return response()->json([[
                'message' => 'Unauthorized.'
            ]], 401);
        }

        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
        ]);

        $course = Course::findOrFail($request->course_id);
        $student = User::findOrFail($request->user_id);

        if (! $student->hasRole('student')) {
            return response()->json([
                'message' => 'User must be a student.'
            ], 405);
        }

        if ($student->courses->contains($request->course_id)) {
            return response()->json([
                'message' => 'Student is already enrolled in this course.'
            ], 405);
        }
        
        $course->students()->attach($student->id);

        return response()->json([
            'message' => 'Successfully registered student!'
        ], 200);
    }

    // student self-registers to course
    public function registerSelf(Request $request) {
        $request->validate([
            'course_id' => 'required|integer',
        ]);

        $user = auth()->user();

        if (! $user->hasRole('student')) {
            return response()->json([
                'message' => 'User must be a student.'
            ], 405);
        }

        if ($user->courses->contains($request->course_id)) {
            return response()->json([
                    'message' => 'You are already enrolled in this course.'
                ], 405);
        }

        $user->courses()->attach($request->course_id);

        return response()->json([
            'message' => 'Successfully enrolled!'
        ], 200);
    }

    // get all courses
    public function viewAll(Request $request) {
        $courses = Course::all();
        return response()->json(CourseResource::collection($courses), 200);
    }

    // get courses associated with user
    public function viewRegisteredCourses(Request $request) {
        $user = auth()->user();
        return response()->json(CourseResource::collection($user->courses), 200);
    }

}
