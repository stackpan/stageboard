<?php

namespace App\Http\Controllers\Web;

use App\Dto\ColumnDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateColumnRequest;
use App\Http\Requests\MoveColumnRequest;
use App\Http\Requests\UpdateColumnRequest;
use App\Services\ColumnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ColumnWebController extends Controller
{
    public function __construct(
        private readonly ColumnService $columnService,
    )
    {
        //
    }

    public function store(CreateColumnRequest $request, string $boardId): RedirectResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            order: $validated['order'],
            color: $validated['color'] ?? null,
        );

        $this->columnService->create($boardId, $data);

        return back();
    }

    public function update(UpdateColumnRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            color: $validated['color'],
        );

        $this->columnService->update($id, $data);

        return back();
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->columnService->delete($id);

        return back();
    }

    public function swap(MoveColumnRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $this->columnService->move($id, $validated['order']);

        return back();
    }
}
