<?php

namespace App\Http\Resources\Api;

use App\Enum\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'phone_number' => $this->phone_number ?? 'N/A',
            'email' => $this->email ?? 'N/A',
            'status' => $this->status ?? 'N/A',
            'status_name' => __(Str::title($this->status->name)),
            'badge_name' => UserStatus::getBadge($this->status->value),
            'role' => $this->roles->pluck('name')->implode(', '),
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'email_verified' => $this->email_verified_at !== null,
            // 'avatar_url' => $this->avatar_url,
        ];
    }
}
