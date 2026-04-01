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
        $permissions = (function () {
            $names = $this->getPermissionNames()->toArray();
            if (!empty($names)) {
                return $names;
            }

            $this->loadMissing('roles.permissions');
            return $this->roles
                ->flatMap(fn($role) => $role->permissions->pluck('name'))
                ->unique()
                ->values()
                ->toArray();
        })();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name ?? 'N/A',
            'last_name' => $this->last_name ?? 'N/A',
            'phone_number' => $this->phone_number ?? 'N/A',
            'email' => $this->email ?? 'N/A',
            'birth_date' => $this->birth_date ?? 'N/A',
            'birth_date_formatted' => $this->birth_date ? $this->birth_date->format('d-m-Y') : 'N/A',
            'status' => $this->status ?? 'N/A',
            'status_name' => __(Str::title($this->status->name)),
            'role_id' => $this->roles->pluck('id')->first(),
            'role' => $this->roles->pluck('name')->implode(', '),
            'permissions' => $permissions,
            'department' => DepartmentResource::make($this->whenLoaded('department', $this->department)),
            'email_verified' => $this->email_verified_at !== null,
        ];
    }
}
