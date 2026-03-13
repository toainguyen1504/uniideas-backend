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
        'id'   => $this->id,
        'name' => $this->name,

        // Ngày đóng raw
        'closure_date' => $this->closure_date,
        // Ngày đóng formatted
        'closure_date_formatted' => $this->closure_date
            ? $this->closure_date->setTimezone('Asia/Ho_Chi_Minh')->format('H:i | d/m/Y')
            : null,

        // Ngày đóng cuối raw
        'final_closure_date' => $this->final_closure_date,
        // Ngày đóng cuối formatted
        'final_closure_date_formatted' => $this->final_closure_date
            ? $this->final_closure_date->setTimezone('Asia/Ho_Chi_Minh')->format('H:i | d/m/Y')
            : null,

        'status' => [
            'value' => $this->status->value,
        ],
        'is_closed'       => $this->is_closed,
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
