<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\AnonymousEnum;
use Illuminate\Support\Str;

class ReactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'user' => $this->whenLoaded('user', UserResource::make($this->user)),
            'id' => $this->id,
            'user' => $this->whenLoaded('user', $this->user->only(['id', 'name', 'email'])),
            'idea_id' => $this->idea_id,
            'react' => $this->react,
            'react_name' => $this->react->getName(),
            'is_anonymous' => $this->is_anonymous instanceof AnonymousEnum
                ? $this->is_anonymous->value
                : AnonymousEnum::NOT_ANONYMOUS->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
