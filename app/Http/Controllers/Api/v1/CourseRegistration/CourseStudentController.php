<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\v1\CourseStudentResource;
use App\Models\Course;

/**
 * @group Courses
 */
class CourseStudentController extends ApiController
{
    /**
     * Get a list of all courses with students
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\CourseStudentResource
     * @apiResourceModel App\Models\Course
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $courses = Course::all();
        return $this->okWithData(CourseStudentResource::collection($courses));
    }
}
