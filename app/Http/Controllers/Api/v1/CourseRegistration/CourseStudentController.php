<?php

namespace App\Http\Controllers\Api\v1\CourseRegistration;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CourseStudentResource;
use App\Models\Course;

class CourseStudentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $courses = Course::all();
        return CourseStudentResource::collection($courses);
    }
}
