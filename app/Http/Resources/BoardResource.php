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
            'thumbnail_url' => $this->thumbnail_url,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'opened_at' => $this->opened_at,
            'links' => [
                'self' => [
                    'href' => route('api.boards.show', $this->id),   
                ],
            ],
        ];
    }
}
