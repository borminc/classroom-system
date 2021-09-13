<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|unique:users,username,' . ($this && $this->user ? $this->user->id : null),
            'email' => 'required|string|unique:users,email,' . ($this && $this->user ? $this->user->id : null),
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|distinct|exists:roles,id',
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
            'username' => [
                'description' => 'A unique username',
            ],
            'email' => [
                'description' => 'A unique email address',
            ],
            'first_name' => [
                'description' => 'First name of user',
            ],
            'last_name' => [
                'description' => 'Last name of user',
            ],
            'gender' => [
                'description' => 'Gender of the user',
            ],
            'date_of_birth' => [
                'description' => 'The date of birth of user',
                'example' => '2020-12-01',
            ],
            'role_ids' => [
                'description' => 'An array of ids of roles',
            ],
        ];
    }
}
