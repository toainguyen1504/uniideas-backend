<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'per_page'     => $this->resource->perPage() ?? 5,
                'current_page' => $this->resource->currentPage(),
                'last_page'    => $this->resource->lastPage(),
                'total'        => $this->resource->total(),
            ],
        ];
    }
}
