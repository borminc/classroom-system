<?php

namespace App\Http\Requests\v1\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class AssignPermissionToRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('assignPermissions', Permission::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'required|integer|exists:roles,id',
            'permission_ids' => 'required|array|min:1',
            'permission_ids.*' => 'required|integer|distinct|exists:permissions,id',
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
            'role_id' => [
                'description' => 'The id of the role',
            ],
            'permission_ids' => [
                'description' => 'An array of ids of permissions',
            ],
        ];
    }
}
