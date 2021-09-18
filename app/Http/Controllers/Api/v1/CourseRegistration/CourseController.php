<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\v1\Course\StoreCourseRequest;
use App\Http\Requests\v1\Course\UpdateCourseRequest;
use App\Http\Resources\v1\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

/**
 * @group Courses
 *
 * API endpoints for getting info about courses and creating courses
 */
class CourseController extends ApiController
{
    /**
     * Get a list of all courses
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $courses = Course::all();
        return $this->okWithData(CourseResource::collection($courses));
    }

    /**
     * Create a new course
     *
     * @authenticated
     * @param StoreCourseRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());
        return $this->created(new CourseResource($course));
    }

    /**
     * Get the specified course.
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @return Illuminate\Http\JsonResponse
     * @apiResource App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);
        return $this->okWithData(new CourseResource($course));
    }

    /**
     * Update the specified course
     *
     * @authenticated
     * @param  UpdateCourseRequest  $request
     * @param  \App\Models\Course  $course
     * @return Illuminate\Http\JsonResponse
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());
        return $this->updated(new CourseResource($course));
    }

    /**
     * Delete the specified course
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return $this->deleted(new CourseResource($course));
    }
}
