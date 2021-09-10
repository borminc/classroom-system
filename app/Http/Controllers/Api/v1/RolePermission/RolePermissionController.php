<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RolePermission\AssignPermissionToRoleRequest;
use App\Http\Requests\v1\RolePermission\AssignPermissionToUserRequest;
use App\Http\Requests\v1\RolePermission\RevokePermissionFromRoleRequest;
use App\Http\Requests\v1\RolePermission\RevokePermissionFromUserRequest;
use App\Http\Resources\v1\PermissionResource;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
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
            return response()->json([
                'message' => 'No permission was assigned to role',
            ], 200);
        }

        return response()->json([
            'message' => 'Successfully assigned permissions to role',
            'assigned_permissions' => PermissionResource::collection($assignedPermissions),
        ], 200);
    }

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
            return response()->json([
                'message' => 'No permission was assigned to user',
            ], 200);
        }

        return response()->json([
            'message' => 'Successfully assigned permissions to user',
            'assigned_permissions' => PermissionResource::collection($assignedPermissions),
        ], 200);
    }

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
            return response()->json([
                'message' => 'No permission was revoked',
            ], 200);
        }

        return response()->json([
            'message' => 'Successfully revoked permissions from role',
            'revoked_permissions' => PermissionResource::collection($revokedPermissions),
        ], 200);
    }

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
            return response()->json([
                'message' => 'No permission was revoked',
            ], 200);
        }

        return response()->json([
            'message' => 'Successfully revoked permissions from user',
            'revoked_permissions' => PermissionResource::collection($revokedPermissions),
        ], 200);
    }
}