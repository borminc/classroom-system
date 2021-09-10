<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Role\StoreRoleRequest;
use App\Http\Requests\v1\Role\UpdateRoleRequest;
use App\Http\Resources\v1\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Roles
 *
 * API endpoints for managing roles
 */
class RoleController extends Controller
{
    /**
     * Display all roles.
     *
     * @authenticated
     * @return \Illuminate\Http\Response
     * @apiResourceCollection App\Http\Resources\v1\RoleResource
     * @apiResourceModel Spatie\Permission\Models\Role
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a new role.
     *
     * @authenticated
     * @param  StoreRoleRequest  $request
     * @bodyParam name string required A unique role name
     *
     * @return \Illuminate\Http\Response
     * @response 201 {
     *  "message": "Successfully created role!"
     * }
     */
    public function store(StoreRoleRequest $request)
    {
        Role::create($request->validated());
        return response()->json([
            'message' => 'Successfully created role!',
        ], 201);
    }

    /**
     * Display a specific role.
     *
     * @authenticated
     * @param  int  $id
     * @urlParam id integer required The ID of the role
     *
     * @return \Illuminate\Http\Response
     * @apiResource App\Http\Resources\v1\RoleResource
     * @apiResourceModel Spatie\Permission\Models\Role
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('view', $role);
        return new RoleResource($role);
    }

    /**
     * Update a specific role.
     *
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @urlParam id integer required The ID of the role
     * @bodyParam name string required The name of the role
     *
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully updated role!"
     * }
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['display_name' => $request->name]);
        return response()->json([
            'message' => 'Successfully updated role!',
        ], 200);
    }

    /**
     * Delete a specific role
     *
     * @authenticated
     * @param  int  $id
     * @urlParam id integer required The ID of the role
     *
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully deleted role!"
     * }
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
        return response()->json([
            'message' => 'Successfully deleted role!',
        ], 200);

    }
}
