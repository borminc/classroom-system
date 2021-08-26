<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Http\Resources\v1\CourseResource;

class RegistrationController extends Controller
{
    public function viewStudentRegisteredCourses(Request $request) {
        $this->authorize('viewOwnStudentCourses', Course::class);
        $user = auth()->user();
        return response()->json(CourseResource::collection($user->student_courses), 200);
    }

    public function viewInstructorRegisteredCourses(Request $request) {
        $this->authorize('viewOwnInstructorCourses', Course::class);
        $user = auth()->user();
        return response()->json(CourseResource::collection($user->instructor_courses), 200);
    }

    // admin registers student to course
    public function registerStudentsCourses(Request $request) {
        $this->authorize('registerStudentsCourses', Course::class);

        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
        ]);

        $course = Course::findOrFail($request->course_id);
        $student = User::findOrFail($request->user_id);

        if ($student->cannot('take courses')) {
            return response()->json([
                'message' => 'User cannot take any courses.'
            ], 405);
        }

        if ($student->student_courses->contains($request->course_id)) {
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
    public function selfRegisterCourses(Request $request) {
        $this->authorize('selfRegisterCourses', Course::class);

        $request->validate([
            'course_id' => 'required|integer',
        ]);

        $user = auth()->user();
        if ($user->student_courses->contains($request->course_id)) {
            return response()->json([
                'message' => 'You are already enrolled in this course.'
            ], 405);
        }

        $user->student_courses()->attach($request->course_id);

        return response()->json([
            'message' => 'Successfully enrolled!'
        ], 200);
    }
}
