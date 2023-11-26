<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
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
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),
            'next_column_id' => $this->next_column_id,
            'links' => [
                'self' => [
                    'href' => route('api.boards.columns.show', [$this->board_id, $this->id]),
                ],
                'move' => [
                    'href' => route('api.boards.columns.move', [$this->board_id, $this->id]),
                ],
            ],
        ];
    }
}
