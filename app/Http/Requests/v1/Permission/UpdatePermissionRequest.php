<?php

namespace App\Http\Requests\v1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->permission);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'unique:permissions,display_name,' . ($this && $this->permission ? $this->permission->id : null),
                'unique:permissions,name,' . ($this && $this->permission ? $this->permission->id : null),
            ],
            'group' => 'required|string',
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
                'description' => 'A unique name of the permission',
            ],
            'group' => [
                'description' => 'The group the permission belongs to',
            ],
        ];
    }

}
