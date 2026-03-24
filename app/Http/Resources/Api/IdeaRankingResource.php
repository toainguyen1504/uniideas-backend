<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class IdeaRankingResource extends JsonResource
{
    public function toArray($request): array
    {
        $likes = app(\App\Repositories\React\ReactRepositoryInterface::class)->countLikesByIdea($this->id);
        $dislikes = app(\App\Repositories\React\ReactRepositoryInterface::class)->countDislikesByIdea($this->id);
        $comments = $this->comments()->count();

        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'views'          => $this->total_views ?? 0,
            'likes_count'    => $likes,
            'dislikes_count' => $dislikes,
            'comments_count' => $this->total_comments ?? 0,
            'score'          => $likes - $dislikes,
            'created_at'     => $this->created_at->toDateString(),
        ];
    }
}
