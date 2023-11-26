<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
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
            'thumbnail_url' => $this->whenHas('thumbnail_url'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'opened_at' => $this->whenHas('opened_at'),
            'links' => [
                'self' => [
                    'href' => route('api.boards.show', $this->id),   
                ],
                'columns' => [
                    'href' => route('api.boards.columns.index', $this->id),
                ],
            ],
        ];
    }
}
