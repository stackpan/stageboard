<?php

namespace App\Http\Controllers\Web;

use App\Dto\ColumnDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateColumnRequest;
use App\Http\Requests\MoveColumnRequest;
use App\Http\Requests\UpdateColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Models\Board;
use App\Models\Column;
use App\Services\ColumnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ColumnWebController extends Controller
{
    public function __construct(
        private readonly ColumnService $columnService,
    )
    {
        $this->authorizeResource(Column::class, 'column');
    }

    public function show(Column $column): ColumnResource
    {
        return new ColumnResource($column);
    }

    public function store(CreateColumnRequest $request, Board $board): RedirectResponse
    {
        $this->authorize('createColumn', $board);
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            order: $validated['order'],
            color: $validated['color'] ?? null,
        );

        $this->columnService->create($board, $data);

        return back();
    }

    public function update(UpdateColumnRequest $request, Column $column): RedirectResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            color: $validated['color'],
        );

        $this->columnService->update($column, $data);

        return back();
    }

    public function destroy(Column $column): RedirectResponse
    {
        $this->columnService->delete($column);

        return back();
    }

    public function swap(MoveColumnRequest $request, Column $column): RedirectResponse
    {
        $this->authorize('swap', $column);
        $validated = $request->validated();

        $this->columnService->swap($column, $validated['order']);

        return back();
    }
}
