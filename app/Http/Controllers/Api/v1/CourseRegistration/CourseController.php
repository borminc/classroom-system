<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Resources\v1\CourseResource;

class CourseController extends Controller
{
    // get all courses
    public function viewAll(Request $request) {
        $courses = Course::all();
        return response()->json(CourseResource::collection($courses), 200);
    }

    public function create(Request $request) {
        $this->authorize('create', Course::class);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'required|string',
            'instructor_id' => 'required|integer'
        ]);

        Course::create($request->all());

        return response()->json([
            'message' => 'Successfully created course!'
        ], 201);
    }
}
