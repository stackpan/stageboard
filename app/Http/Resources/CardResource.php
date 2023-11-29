<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'body' => $this->body,
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),
            'links' => [
                'self' => [
                    'href' => route('api.columns.cards.show', [$this->board_id, $this->column_id, $this->id]),
                ],
                'move' => [
                    'href' => route('api.columns.cards.move', [$this->board_id, $this->column_id, $this->id]),
                ],
            ],
        ];
    }
}
