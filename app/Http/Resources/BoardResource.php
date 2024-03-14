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
            'isPublic' => $this->is_public,
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),
            'thumbnailUrl' => $this->whenHas('thumbnail_url'),
            'user' => [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
            ],
            'openedAt' => $this->users[0]->pivot->opened_at ?? '',
        ];

        if ($this->withRelations) {
            $result['columns'] = ColumnResource::collection($this->whenLoaded('columns'));
        }

        return $result;
    }
}
