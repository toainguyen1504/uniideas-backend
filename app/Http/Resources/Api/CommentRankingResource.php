<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;


class CommentRankingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'idea_id'    => $this->idea_id,
            'idea_title' => $this->idea?->title,
            'user_name'  => $this->user?->name,
            'created_at' => $this->created_at->format('j-n-Y H:i'),

        ];
    }
}
