<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'completed_at' => $this->completed_at,
            'is_completed' => $this->is_completed,
            'user_id' => $this->user_id,

        ];
    }
}
