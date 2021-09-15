<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\v1\PermissionResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name ?? $this->name,
            'permissions' => PermissionResource::collection($this->getAllPermissions()),
        ];
    }
}
