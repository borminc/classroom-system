<?php

namespace App\Http\Controllers\Api\v1\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Permission\UpdatePermissionRequest;
use App\Http\Resources\v1\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

/**
 * @group Permission
 *
 * API endpoints for managing permission
 */
class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     *
     * @authenticated
     * @return PermissionResource
     * @apiResourceCollection App\Http\Resources\v1\PermissionResource
     * @apiResourceModel Spatie\Permission\Models\Permission
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        return PermissionResource::collection(Permission::all());
    }

    /**
     * Display the specified permission.
     *
     * @authenticated
     * @param  Permission $permission
     * @return PermissionResource
     * @apiResource App\Http\Resources\v1\PermissionResource
     * @apiResourceModel Spatie\Permission\Models\Permission
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return new PermissionResource($permission);
    }

    /**
     * Update the specified permission.
     *
     * @authenticated
     * @param  UpdatePermissionRequest  $request
     * @param  Permission $permission
     *
     * @return \Illuminate\Http\Response
     * @response {
     *  "message": "Successfully updated permission!"
     * }
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

    /**
     * Get all permissions by groups
     *
     * @authenticated
     * @return \Illuminate\Database\Eloquent\Collection
     * @response {
     *  "Student permission": [
     *        {
     *            "id": 19,
     *            "name": "take courses",
     *            "display_name": "take courses",
     *            "group": "Student permission"
     *        }
     *    ],
     *    "Instructor permission": [
     *        {
     *            "id": 22,
     *            "name": "teach courses",
     *            "display_name": "teach courses",
     *            "group": "Instructor permission"
     *        },
     *        {
     *            "id": 23,
     *            "name": "view own-instructor-courses",
     *            "display_name": "view own-instructor-courses",
     *            "group": "Instructor permission"
     *        }
     *    ]
     *}
     */
    public function getPermissionsByGroups()
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = Permission::all();
        $permissions->makeHidden(['guard_name', 'created_at', 'updated_at']);
        foreach ($permissions as $permission) {
            if (!$permission->display_name) {
                $permission->display_name = $permission->name;
            }
        }
        return $permissions->groupBy('group');
    }
}
