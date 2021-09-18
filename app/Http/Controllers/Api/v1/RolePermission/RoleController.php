<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\v1\Role\StoreRoleRequest;
use App\Http\Requests\v1\Role\UpdateRoleRequest;
use App\Http\Resources\v1\RolePermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Roles
 *
 * API endpoints for managing roles
 */
class RoleController extends ApiController
{
    /**
     * Display all roles.
     *
     * @authenticated
     * @return Illuminate\Http\JsonResponse
     * @apiResourceCollection App\Http\Resources\v1\RolePermissionResource
     * @apiResourceModel Spatie\Permission\Models\Role
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return $this->okWithData(RolePermissionResource::collection(Role::all()));
    }

    /**
     * Store a new role.
     *
     * @authenticated
     * @param  StoreRoleRequest  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());
        return $this->created(new RolePermissionResource($role));
    }

    /**
     * Display a specific role.
     *
     * @authenticated
     * @param  Spatie\Permission\Models\Role $role
     * @return Illuminate\Http\JsonResponse
     * @apiResource App\Http\Resources\v1\RolePermissionResource
     * @apiResourceModel Spatie\Permission\Models\Role
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);
        return $this->okWithData(new RolePermissionResource($role));
    }

    /**
     * Update a specific role.
     *
     * @authenticated
     * @param  UpdateRoleRequest  $request
     * @param  Spatie\Permission\Models\Role $role
     * @return Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['display_name' => $request->name]);
        return $this->updated(new RolePermissionResource($role));
    }

    /**
     * Delete a specific role
     *
     * @authenticated
     * @param  Spatie\Permission\Models\Role $role
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
        return $this->deleted(new RolePermissionResource($role));
    }
}
