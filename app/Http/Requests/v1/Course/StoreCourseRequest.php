<?php

namespace App\Http\Requests\v1\Course;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:courses',
            'description' => 'required|string',
            'instructor_id' => 'required|integer',
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
            'name' => [
                'description' => 'The name of the course',
            ],
            'code' => [
                'description' => 'The code of the course',
            ],
            'description' => [
                'description' => 'The description of the course',
            ],
            'instructor_id' => [
                'description' => 'The id of the instructor',
            ],
        ];
    }
}
