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
            'aliasId' => $this->alias_id,
            'name' => $this->name,
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),
            'thumbnailUrl' => $this->whenHas('thumbnail_url'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'openedAt' => $this->whenHas('opened_at'),
            'columns' => ColumnResource::collection($this->whenLoaded('columns')),
//            '_links' => [
//                'self' => [
//                    'href' => route('api.boards.show', $this->id),
//                    'rel' => 'self',
//                    'method' => 'GET',
//                ],
//                'update' => [
//                    'href' => route('api.boards.update', $this->id),
//                    'rel' => 'self',
//                    'method' => 'PATCH',
//                ],
//                'delete' => [
//                    'href' => route('api.boards.destroy', $this->id),
//                    'rel' => 'self',
//                    'method' => 'DELETE',
//                ],
//                'columns' => [
//                    'href' => route('api.boards.columns.index', $this->id),
//                    'rel' => 'columns',
//                    'method' => 'GET',
//                ],
//            ],
        ];
    }
}
