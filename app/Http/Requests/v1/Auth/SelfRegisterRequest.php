<?php

namespace App\Http\Requests\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SelfRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
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
                'description' => 'The username user',
            ],
            'email' => [
                'description' => 'A unique email address',
            ],
            'password' => [
                'description' => 'The password of the account',
            ],
            'password_confirmation' => [
                'description' => 'Same as password',
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
        ];
    }
}
