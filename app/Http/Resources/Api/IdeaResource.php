<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;

class IdeaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'content'        => $this->content,
            'intro'          => $this->intro,
            'file_path'      => $this->file_path ?? 'N/A',
            'status' => $this->status instanceof Ideastatus ?
                $this->status?->value
                : Ideastatus::PENDING->value,
            'is_anonymous' => $this->is_anonymous instanceof AnonymousEnum
                ? $this->is_anonymous->value
                : AnonymousEnum::NOT_ANONYMOUS->value,

            'is_featured' => (bool) $this->is_featured,

            'total_views'    => $this->total_views,
            'total_comments' => $this->total_comments,
            'user'           => UserResource::make($this->whenLoaded('user', $this->user)),
            'category'       => CategoryResource::make($this->whenLoaded('category', $this->category)),
            'submission'     => SubmissionResource::make($this->whenLoaded('submission', $this->submission)),
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
