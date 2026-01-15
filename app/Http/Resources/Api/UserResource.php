<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name ?? 'N/A',
            'last_name' => $this->last_name ?? 'N/A',
            'phone_number' => $this->phone_number,
            'email' => $this->email ?? 'N/A',
            'status' => $this->status,
            // 'roles' => RoleResource::collection($this->whenLoaded('roles', $this->roles)),
            'email_verified' => $this->email_verified_at !== null,
            // 'avatar_url' => $this->avatar_url,
        ];
    }
}
