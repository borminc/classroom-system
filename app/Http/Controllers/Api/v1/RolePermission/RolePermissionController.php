<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\v1\RolePermission\AssignPermissionToRoleRequest;
use App\Http\Requests\v1\RolePermission\AssignPermissionToUserRequest;
use App\Http\Requests\v1\RolePermission\RevokePermissionFromRoleRequest;
use App\Http\Requests\v1\RolePermission\RevokePermissionFromUserRequest;
use App\Http\Requests\v1\RolePermission\UpdateRolePermissionRequest;
use App\Http\Resources\v1\PermissionResource;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @group Permission assignment
 *
 * API endpoints for assigning permissions to roles and users
 */
class RolePermissionController extends ApiController
{
    /**
     * Assign permission to role
     *
     * @authenticated
     * @param AssignPermissionToRoleRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function assignPermissionsToRole(AssignPermissionToRoleRequest $request)
    {
        $role = Role::findOrFail($request->role_id);
        $assignedPermissions = collect();

        foreach ($request->permission_ids as $permission_id) {
            $permission = Permission::findOrFail($permission_id);
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
                $assignedPermissions->push($permission);
            }
        }

        if ($assignedPermissions->count() === 0) {
            return $this->okWithMessage('No permission was assigned to role');
        }

        return $this->okWithData([
            'assigned_permissions' => PermissionResource::collection($assignedPermissions),
        ], 'Successfully assigned permissions to role');
    }

    /**
     * Assign permission to user
     *
     * @authenticated
     * @param AssignPermissionToUserRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function assignPermissionsToUser(AssignPermissionToUserRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $assignedPermissions = collect();

        foreach ($request->permission_ids as $permission_id) {
            $permission = Permission::findOrFail($permission_id);
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
                $assignedPermissions->push($permission);
            }
        }

        if ($assignedPermissions->count() === 0) {
            return $this->okWithMessage('No permission was assigned to user');
        }

        return $this->okWithData([
            'assigned_permissions' => PermissionResource::collection($assignedPermissions),
        ], 'Successfully assigned permissions to user');
    }

    /**
     * Revoke permissions from role
     *
     * @authenticated
     * @param RevokePermissionFromRoleRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function revokePermissionFromRole(RevokePermissionFromRoleRequest $request)
    {
        $role = Role::findOrFail($request->role_id);
        $revokedPermissions = collect();

        foreach ($request->permission_ids as $permission_id) {
            $permission = Permission::findOrFail($permission_id);
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                $revokedPermissions->push($permission);
            }
        }

        if ($revokedPermissions->count() === 0) {
            return $this->okWithMessage('No permission was revoked');
        }

        return $this->okWithData([
            'revoked_permissions' => PermissionResource::collection($revokedPermissions),
        ], 'Successfully revoked permissions from role');
    }

    /**
     * Revoke permissions from user
     *
     * @authenticated
     * @param RevokePermissionFromUserRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function revokePermissionFromUser(RevokePermissionFromUserRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $revokedPermissions = collect();

        foreach ($request->permission_ids as $permission_id) {
            $permission = Permission::findOrFail($permission_id);
            if ($user->hasPermissionTo($permission)) {
                $user->revokePermissionTo($permission);
                $revokedPermissions->push($permission);
            }
        }

        if ($revokedPermissions->count() === 0) {
            return $this->okWithMessage('No permission was revoked');
        }

        return $this->okWithData([
            'revoked_permissions' => PermissionResource::collection($revokedPermissions),
        ], 'Successfully revoked permissions from user');
    }

    /**
     * Update permissions of role
     *
     * @authenticated
     * @param UpdateRolePermissionRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function updateRolePermissions(UpdateRolePermissionRequest $request)
    {
        // permission ids in the selected groups
        $selected_permission_ids = Permission::whereIn('group', $request->groups)->pluck('id');

        // verify that the permission ids provided are in the groups
        foreach ($request->roles_permissions as $role_permissions) {
            $updated_permission_ids = $role_permissions['permission_ids'];
            if (array_intersect($updated_permission_ids, $selected_permission_ids->toArray()) != $updated_permission_ids) {
                return $this->errors([
                    'permission_ids' => 'The permission_ids are not in the selected groups.',
                ]);
            }
        }

        // for each role's permissions
        foreach ($request->roles_permissions as $role_permissions) {
            $role = Role::findOrFail($role_permissions['role_id']);

            // permission ids that the role should have
            $updated_permission_ids = collect($role_permissions['permission_ids']);

            foreach ($selected_permission_ids as $permission_id) {
                $permission = Permission::findOrFail($permission_id);

                if ($updated_permission_ids->contains($permission_id)) {
                    if (!$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                    }
                } else {
                    if ($role->hasPermissionTo($permission)) {
                        $role->revokePermissionTo($permission);
                    }
                }
            }
        }
        return $this->okWithMessage('Successfully updated permissions!');
    }
}
