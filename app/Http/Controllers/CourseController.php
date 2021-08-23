<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function create(Request $request) {
        if (! auth()->user()->hasRole('admin')) {
            return response()->json([[
                'message' => 'Unauthorized.'
            ]], 401);
        }

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
