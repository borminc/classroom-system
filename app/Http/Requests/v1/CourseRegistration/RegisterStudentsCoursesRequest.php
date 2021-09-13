<?php

namespace App\Http\Requests\v1\CourseRegistration;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentsCoursesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('registerStudentsCourses', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }

    /**
     * Get the body params in the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'user_id' => [
                'description' => 'The user_id of the student to be registered',
            ],
            'course_id' => [
                'description' => 'The id of the course',
            ],
        ];
    }
}
