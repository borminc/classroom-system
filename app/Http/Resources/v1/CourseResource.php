<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'code' => $this->code,
            'description' => $this->description,
            'instructor' => new UserResource(User::findOrFail($this->instructor_id)),
        ];
    }
}
