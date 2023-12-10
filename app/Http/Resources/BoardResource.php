<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    public function __construct(
        $resource,
        private readonly ?bool $withRelations = true,
    )
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id,
            'aliasId' => $this->alias_id,
            'name' => $this->name,
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),
            'thumbnailUrl' => $this->whenHas('thumbnail_url'),
            'user' => [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
            ],
            'openedAt' => $this->whenHas('opened_at'),
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

        if ($this->withRelations) {
            $result['columns'] = ColumnResource::collection($this->whenLoaded('columns'));
        }

        return $result;
    }
}
