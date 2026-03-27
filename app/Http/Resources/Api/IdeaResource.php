<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;
use App\Repositories\React\ReactRepository;
use App\Repositories\React\ReactRepositoryInterface;
use Illuminate\Support\Str;

class IdeaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $likes = app(ReactRepositoryInterface::class)->countLikesByIdea($this->id);
        $dislikes = app(ReactRepositoryInterface::class)->countDislikesByIdea($this->id);
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'content'        => $this->content,
            'intro'          => $this->intro,
            'file_path'      => $this->file_path ?? 'N/A',
            'status' => $this->status instanceof IdeaStatus
                ? $this->status?->value
                : IdeaStatus::PENDING->value,
            'status_name' => __(Str::title($this->status->name)),
            'is_anonymous' => $this->is_anonymous instanceof AnonymousEnum
                ? $this->is_anonymous->value
                : AnonymousEnum::NOT_ANONYMOUS->value,
            'is_anonymous_name' => __(Str::title(str_replace('_', ' ', $this->is_anonymous->name))),
            'is_featured' => (bool) $this->is_featured,
            'likes_count'    => $likes,
            'dislikes_count' => $dislikes,
            'score'          => $likes - $dislikes,
            'total_views'    => $this->total_views,
            'total_comments' => $this->total_comments,
            'terms_conditions' => (bool) $this->terms_conditions,
            'user'           => UserResource::make($this->whenLoaded('user', $this->user)),
            'category'       => CategoryResource::make($this->whenLoaded('category', $this->category)),
            'submission'     => SubmissionResource::make($this->whenLoaded('submission', $this->submission)),
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
            'reacts' => ReactResource::collection($this->whenLoaded('reacts', $this->reacts)),
            'comments' => CommentResource::collection($this->whenLoaded('comments', $this->comments)),
        ];
    }
}
