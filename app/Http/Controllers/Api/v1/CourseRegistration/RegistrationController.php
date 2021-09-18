<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\v1\CourseRegistration\RegisterStudentsCoursesRequest;
use App\Http\Requests\v1\CourseRegistration\SelfRegisterCoursesRequest;
use App\Http\Resources\v1\CourseResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Course Registration
 *
 * API endpoints for registering courses
 */
class RegistrationController extends ApiController
{
    /**
     * Get students' registered courses
     *
     * @authenticated
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function viewStudentRegisteredCourses(Request $request)
    {
        $this->authorize('viewOwnStudentCourses', Course::class);
        $user = $request->user();
        return $this->okWithData(CourseResource::collection($user->student_courses));
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
        $user = $request->user();
        return $this->okWithData(CourseResource::collection($user->instructor_courses));
    }

    /**
     * Register students' courses by admin
     *
     * @authenticated
     * @param RegisterStudentsCoursesRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function registerStudentsCourses(RegisterStudentsCoursesRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        $student = User::findOrFail($request->user_id);

        if ($student->cannot('take courses')) {
            return $this->errorMessage('User cannot take any courses.');
        }

        if ($student->student_courses->contains($request->course_id)) {
            return $this->errorMessage('Student is already enrolled in this course.');
        }

        $course->students()->attach($student->id);

        return $this->okWithMessage('Successfully registered student!');
    }

    /**
     * Self-register course by students
     *
     * @authenticated
     * @param SelfRegisterCoursesRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function selfRegisterCourses(SelfRegisterCoursesRequest $request)
    {
        $user = $request->user();

        if ($user->student_courses->contains($request->course_id)) {
            return $this->errorMessage('You are already enrolled in this course.');
        }

        $user->student_courses()->attach($request->course_id);

        return $this->okWithMessage('Successfully enrolled!');
    }
}
