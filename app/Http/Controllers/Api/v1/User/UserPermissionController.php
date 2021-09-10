<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserPermissionResource;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function getAllUsersWithPermissions()
    {
        $this->authorize('viewAny', Permission::class);
        return UserPermissionResource::collection(User::all());
    }
}
