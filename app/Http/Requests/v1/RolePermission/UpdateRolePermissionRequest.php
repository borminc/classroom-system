<?php

namespace App\Http\Requests\v1\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (
            $this->user()->can('revokePermissions', Permission::class) &&
            $this->user()->can('assignPermissions', Permission::class)
        );
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
            'roles_permissions' => 'required|array|min:1',
            'roles_permissions.*.role_id' => 'required|integer|exists:roles,id',
            'roles_permissions.*.permission_ids' => 'present|array',
            'roles_permissions.*.permission_ids.*' => 'present|integer|exists:permissions,id',
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
            'permission_ids' => [
                'description' => 'An array of ids of permissions',
            ],
        ];
    }
}
