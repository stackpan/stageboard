<?php

namespace App\Repositories\Impl;

use App\Dto\ColumnDto;
use App\Enums\ColumnColor;
use App\Models\Board;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Database\Eloquent\Collection;

class ColumnRepositoryImpl implements ColumnRepository
{
    public function getAllByBoard(Board $board): Collection
    {
        return Column::whereBoardId($board->id)
            ->with('cards')
            ->get();
    }

    public function getCountByBoard(Board $board): int
    {
        return Column::whereBoardId($board->id)->count();
    }

    public function create(Board $board, ColumnDto $data): string
    {
        $color = $data->color ?? fake()->randomElement(ColumnColor::class);

        return $board->columns()
            ->create([
                'name' => $data->name,
                'order' => $data->order,
                'color' => $color,
            ])
            ->id;
    }

    public function generate(Board $board): void
    {
        $data = collect([
            ['name' => 'Open', 'color' => ColumnColor::BLUE],
            ['name' => 'In Progress', 'color' => ColumnColor::AMBER],
            ['name' => 'Done', 'color' => ColumnColor::LIME],
            ['name' => 'Rejected', 'color' => ColumnColor::RED]
        ]);

        $data->each(fn (array $item, int $key) => Column::factory()
            ->for($board)
            ->create([
                'name' => $item['name'],
                'color' => $item['color'],
                'order' => $key
            ])
        );
    }

    public function getById(string $id, ?bool $nullable = false, ?bool $withRelation = true): ?Column
    {
        $query = Column::whereId($id);

        if ($withRelation) {
            $query->with('cards');
        }

        return $nullable ? $query->first() : $query->firstOrFail();
    }

    public function update(Column $column, ColumnDto $data): void
    {
        $column->update([
                'name' => $data->name,
                'color' => $data->color,
            ]);
    }

    public function delete(Column $column): void
    {
        $boardId = $column->board_id;
        $columnOrder = $column->order;

        $column->delete();
        $this->unshift($boardId, $columnOrder);
    }

    public function shift(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->increment('order');
    }

    public function unshift(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->decrement('order');
    }

    public function swap(Column $column, int $toOrder): void
    {
        $column->update([
                'order' => $toOrder,
            ]);
    }
    public function countByBoard(Board $board): int
    {
        return Column::whereBoardId($board->id)->count();
    }
}
