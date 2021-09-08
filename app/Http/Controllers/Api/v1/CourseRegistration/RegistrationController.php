<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CourseResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Course Registration
 *
 * API endpoints for registering courses
 */
class RegistrationController extends Controller
{
    /**
     * Get students' registered courses
     *
     * @authenticated
     * @param Request $request
     * @return CourseResource
     * @apiResourceCollection App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function viewStudentRegisteredCourses(Request $request)
    {
        $this->authorize('viewOwnStudentCourses', Course::class);
        $user = auth()->user();
        return CourseResource::collection($user->student_courses);
    }

    /**
     * Get instructors' registered courses
     *
     * @authenticated
     * @param Request $request
     * @return CourseResource
     * @apiResourceCollection App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function viewInstructorRegisteredCourses(Request $request)
    {
        $this->authorize('viewOwnInstructorCourses', Course::class);
        $user = auth()->user();
        return CourseResource::collection($user->instructor_courses);
    }

    /**
     * Register students' courses by admin
     *
     * @authenticated
     * @param Request $request
     * @bodyParam user_id int required The user_id of the student to be registered
     * @bodyParam course_id int required The id of the course
     *
     * @return Illuminate\Http\JsonResponse
     * @response 200 {
     *  "message": "Successfully registered student!"
     * }
     * @response 405 {
     *  "message": "User cannot take any courses."
     * }
     */
    public function registerStudentsCourses(Request $request)
    {
        $this->authorize('registerStudentsCourses', Course::class);

        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
        ]);

        $course = Course::findOrFail($request->course_id);
        $student = User::findOrFail($request->user_id);

        if ($student->cannot('take courses')) {
            return response()->json([
                'message' => 'User cannot take any courses.',
            ], 405);
        }

        if ($student->student_courses->contains($request->course_id)) {
            return response()->json([
                'message' => 'Student is already enrolled in this course.',
            ], 405);
        }

        $course->students()->attach($student->id);

        return response()->json([
            'message' => 'Successfully registered student!',
        ], 200);
    }

    /**
     * Self-register course by students
     *
     * @authenticated
     * @param Request $request
     * @bodyParam course_id int required The id of the course
     *
     * @return Illuminate\Http\JsonResponse
     * @response 200 {
     *  "message": "Successfully enrolled!"
     * }
     * @response 405 {
     *  "message": "You are already enrolled in this course."
     * }
     */
    public function selfRegisterCourses(Request $request)
    {
        $this->authorize('selfRegisterCourses', Course::class);

        $request->validate([
            'course_id' => 'required|integer',
        ]);

        /** @var App\Models\User */
        $user = auth()->user();

        if ($user->student_courses->contains($request->course_id)) {
            return response()->json([
                'message' => 'You are already enrolled in this course.',
            ], 405);
        }

        $user->student_courses()->attach($request->course_id);

        return response()->json([
            'message' => 'Successfully enrolled!',
        ], 200);
    }
}
