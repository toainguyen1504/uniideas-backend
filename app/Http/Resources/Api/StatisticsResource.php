<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_ideas' => $this['total_ideas'],
            'total_likes' => $this['total_likes'],
            'total_dislikes' => $this['total_dislikes'],
            'total_comments' => $this['total_comments'],
            'by_department' => DepartmentStatisticsResource::collection($this['by_department']),
        ];
    }
}
