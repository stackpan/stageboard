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
            'order' => $this->order,
            'cards' => CardResource::collection($this->whenLoaded('cards')),
            '_links' => [
                'self' => [
                    'href' => route('api.columns.show', $this->id),
                    'rel' => 'self',
                    'method' => 'GET',
                ],
                'update' => [
                    'href' => route('api.columns.update', $this->id),
                    'rel' => 'self',
                    'method' => 'PATCH',
                ],
                'delete' => [
                    'href' => route('api.columns.destroy', $this->id),
                    'rel' => 'self',
                    'method' => 'DELETE',
                ],
                'swap' => [
                    'href' => route('api.columns.swap', $this->id),
                    'rel' => 'self',
                    'method' => 'PATCH',
                ],
                'cards' => [
                    'href' => route('api.columns.cards.index', $this->id),
                    'rel' => 'cards',
                    'method' => 'GET',
                ],
            ],
        ];
    }
}
