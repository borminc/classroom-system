<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
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
class CourseController extends Controller
{
    /**
     * Get a list of all courses
     *
     * @authenticated
     * @return CourseResource
     * @apiResourceCollection App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);

        $courses = Course::all();
        return CourseResource::collection($courses);
    }

    /**
     * Create a new course
     *
     * @authenticated
     * @param StoreCourseRequest $request
     * @return Illuminate\Http\JsonResponse
     * @response 201 {
     *  "message": "Successfully created course!"
     * }
     */
    public function store(StoreCourseRequest $request)
    {
        Course::create($request->validated());
        return response()->json([
            'message' => 'Successfully created course!',
        ], 201);
    }

    /**
     * Get the specified course.
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     * @apiResource App\Http\Resources\v1\CourseResource
     * @apiResourceModel App\Models\Course
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);
        return new CourseResource($course);
    }

    /**
     * Update the specified course
     *
     * @authenticated
     * @param  UpdateCourseRequest  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully updated course!"
     * }
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());
        return response()->json([
            'message' => 'Successfully updated course!',
        ], 200);
    }

    /**
     * Delete the specified course
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully deleted user!"
     * }
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();
        return response()->json([
            'message' => 'Successfully deleted course!',
        ], 200);
    }
}
