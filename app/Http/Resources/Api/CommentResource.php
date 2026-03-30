<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\AnonymousEnum;
use Illuminate\Support\Str;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->whenLoaded('user', UserResource::make($this->user)),
            'id' => $this->id,
            'content' => $this->content,
            'is_anonymous' => $this->is_anonymous instanceof AnonymousEnum
                ? $this->is_anonymous->value
                : AnonymousEnum::NOT_ANONYMOUS->value,
            'is_anonymous_name' => __(Str::title(str_replace('_', ' ', $this->is_anonymous->name))),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
