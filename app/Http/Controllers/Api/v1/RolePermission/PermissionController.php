<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\v1\Permission\UpdatePermissionRequest;
use App\Http\Resources\v1\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

/**
 * @group Permission
 *
 * API endpoints for managing permission
 */
class PermissionController extends ApiController
{
    /**
     * Display a listing of permissions.
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\PermissionResource
     * @apiResourceModel Spatie\Permission\Models\Permission
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        return $this->okWithData(PermissionResource::collection(Permission::all()));
    }

    /**
     * Display the specified permission.
     *
     * @authenticated
     * @param  Permission $permission
     * @return Illuminate\Http\JsonResponse
     * @apiResource App\Http\Resources\v1\PermissionResource
     * @apiResourceModel Spatie\Permission\Models\Permission
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return $this->okWithData(new PermissionResource($permission));
    }

    /**
     * Update the specified permission.
     *
     * @authenticated
     * @param  UpdatePermissionRequest  $request
     * @param  Permission $permission
     * @return Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'display_name' => $request->name,
            'group' => $request->group,
        ]);

        return $this->updated(new PermissionResource($permission));
    }

    /**
     * Get all permissions by groups
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     */
    public function getPermissionsByGroups()
    {
        $this->authorize('viewAny', Permission::class);
        $group_permissions = PermissionResource::collection(Permission::all())->groupBy('group');
        return $this->okWithData([
            'groups' => array_keys($group_permissions->toArray()),
            'group_permissions' => $group_permissions,
        ]);
    }
}
