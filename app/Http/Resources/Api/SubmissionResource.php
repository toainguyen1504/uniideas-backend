<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
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
            'closure_date' => $this->closure_date,
            'final_closure_date' => $this->final_closure_date,
            'status' => $this->when($this->status, fn() => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ]),

            'is_closed' => $this->when(isset($this->is_closed), $this->is_closed),
            'is_final_closed' => $this->when(isset($this->is_final_closed), $this->is_final_closed),
            'ideas_count' => $this->whenCounted('ideas'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
