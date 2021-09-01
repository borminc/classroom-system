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
    public function viewAll(Request $request)
    {
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
    public function create(Request $request)
    {
        $this->authorize('create', Course::class);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'required|string',
            'instructor_id' => 'required|integer',
        ]);

        Course::create($request->all());

        return response()->json([
            'message' => 'Successfully created course!',
        ], 201);
    }
}
