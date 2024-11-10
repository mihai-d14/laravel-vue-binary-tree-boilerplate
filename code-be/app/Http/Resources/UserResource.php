<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'tree_type' => $this->tree_type,
            'parent_id' => $this->parent_id,
            '_lft' => $this->_lft,
            '_rgt' => $this->_rgt,
            'children' => UserResource::collection($this->whenLoaded('children')),
            'parent' => new UserResource($this->whenLoaded('parent')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}