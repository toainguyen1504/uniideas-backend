<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentStatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'department_id'   => $this->department_id,
            'department_name' => $this->department_name,
            'ideas_count'     => $this->ideas_count,
            'likes_count'     => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'comments_count'  => $this->comments_count,
        ];
    }
}
