<?php

namespace App\Http\Resources\v1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::findOrFail($this->id);
        return [
            'user' => new UserResource($user),
            'permissions' => PermissionResource::collection($user->getAllPermissions()),
        ];
    }
}
