<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => class_basename($this->type), 
            'data' => $this->data,
            'is_read' => !is_null($this->read_at),
            'read_at' => $this->read_at,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at_format' => $this->created_at->format('d-m-Y | H:i:s'),
            'time_ago' => $this->created_at->diffForHumans(),
        ];
    }
}
