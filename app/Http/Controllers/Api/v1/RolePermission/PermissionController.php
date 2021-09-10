<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Permission\UpdatePermissionRequest;
use App\Http\Resources\v1\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        return PermissionResource::collection(Permission::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePermissionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'display_name' => $request->name,
            'group' => $request->group,
        ]);
        return response()->json([
            'message' => 'Successfully updated permission!',
        ], 200);
    }

    public function getPermissionsByGroups()
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = Permission::all()->groupBy('group');
        $permissions->makeHidden(['guard_name', 'created_at', 'updated_at']);
        return $permissions;
    }
}
