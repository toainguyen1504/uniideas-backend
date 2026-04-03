<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\AnonymousEnum;
use Illuminate\Support\Str;


class CommentRankingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'is_anonymous' => $this->is_anonymous instanceof AnonymousEnum
                ? $this->is_anonymous->value
                : AnonymousEnum::NOT_ANONYMOUS->value,
            'is_anonymous_name' => __(Str::title(str_replace('_', ' ', $this->is_anonymous->name))),
            'idea_id'    => $this->idea_id,
            'idea_title' => $this->idea?->title,
            'user_name'  => $this->user?->name,
            'created_at' => $this->created_at->format('j-n-Y H:i'),

        ];
    }
}
