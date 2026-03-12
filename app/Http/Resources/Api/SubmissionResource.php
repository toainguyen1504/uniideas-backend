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
        'closure_date' => $this->closure_date
            ? $this->closure_date->setTimezone('Asia/Ho_Chi_Minh')->format('j-n-Y H:i')
            : null,
        'final_closure_date' => $this->final_closure_date
            ? $this->final_closure_date->setTimezone('Asia/Ho_Chi_Minh')->format('j-n-Y H:i')
            : null,
        'status' => [
            'value' => $this->status->value,
        ],
        'is_closed' => $this->is_closed,
        'is_final_closed' => $this->is_final_closed,
        'created_at' => $this->created_at
            ? $this->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s')
            : null,
        'updated_at' => $this->updated_at
            ? $this->updated_at->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s')
            : null,
    ];
}
}
