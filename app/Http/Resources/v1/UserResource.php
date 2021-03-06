<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth->format('j/M/Y'),
            'roles' => RoleResource::collection($this->roles),
            'permissions' => PermissionResource::collection($this->getAllPermissions()),
        ];
    }
}
