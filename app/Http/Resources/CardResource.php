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
            'color' => $this->whenHas('color'),
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),
//            '_links' => [
//                'self' => [
//                    'href' => route('api.cards.show', $this->id),
//                    'rel' => 'self',
//                    'method' => 'GET',
//                ],
//                'update' => [
//                    'href' => route('api.cards.update', $this->id),
//                    'rel' => 'self',
//                    'method' => 'PATCH',
//                ],
//                'delete' => [
//                    'href' => route('api.cards.destroy', $this->id),
//                    'rel' => 'self',
//                    'method' => 'DELETE',
//                ],
//                'move' => [
//                    'href' => route('api.cards.move', $this->id),
//                    'rel' => 'self',
//                    'method' => 'PATCH',
//                ],
//            ],
        ];
    }
}
