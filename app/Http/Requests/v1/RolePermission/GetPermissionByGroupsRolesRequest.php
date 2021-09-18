<?php

namespace App\Http\Requests\v1\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class GetPermissionByGroupsRolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('view permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'groups' => 'required|array|min:1',
            'groups.*' => 'required|string|exists:permissions,group',

            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
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
            'groups' => [
                'description' => 'The selected groups of permissions',
            ],
            'role_ids' => [
                'description' => 'An array of ids of roles',
            ],
        ];
    }
}
