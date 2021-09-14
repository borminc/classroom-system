<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\v1\PermissionResource;
use App\Http\Resources\v1\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class RolePermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $role = Role::findOrFail($this->id);
        return [
            'role' => new RoleResource($role),
            'permissions' => PermissionResource::collection($role->getAllPermissions()),
        ];
    }
}
