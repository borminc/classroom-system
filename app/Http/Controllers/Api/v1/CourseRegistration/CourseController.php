<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
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
     * @param Request $request
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
     * @param Request $request
     * @bodyParam name string required The name of the course
     * @bodyParam code string required The code of the course
     * @bodyParam description string required The description of the course
     * @bodyParam instructor_id int required The user_id of the instructor of the course
     *
     * @return Illuminate\Http\JsonResponse
     * @response 201 {
     *  "message": "Successfully created course!"
     * }
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:courses',
            'description' => 'required|string',
            'instructor_id' => 'required|integer',
        ]);

        Course::create($request->all());

        return response()->json([
            'message' => 'Successfully created course!',
        ], 201);
    }

    /**
     * Get the specified course.
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @urlParam id integer required The ID of the course
     *
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
     * @param  \Illuminate\Http\Request  $request
     * @urlParam id integer required The ID of the course
     * @bodyParam name string required The name of the course
     * @bodyParam code string required The code of the course
     * @bodyParam description string required The description of the course
     * @bodyParam instructor_id int required The user_id of the instructor of the course
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully updated course!"
     * }
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'required|string',
            'instructor_id' => 'required|integer',
        ]);

        $course->name = $request->name;
        $course->code = $request->code;
        $course->description = $request->description;
        $course->instructor_id = $request->instructor_id;
        $course->save();

        return response()->json([
            'message' => 'Successfully updated course!',
        ], 200);
    }

    /**
     * Delete the specified course
     *
     * @authenticated
     * @param  \App\Models\Course  $course
     * @urlParam id integer required The ID of the course
     *
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
