<?php

namespace App\Http\Requests;

use App\Enums\ColumnColor;
use App\Models\Board;
use App\Rules\InColumnSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateColumnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $boardId = $this->route('board');
        $board = Board::findOrFail($boardId);

        return [
            'name' => ['required', 'string', 'max:24'],
            'order' => ['required', 'integer', 'min:0', 'max:10', new InColumnSize($board)],
            'color' => ['nullable', Rule::enum(ColumnColor::class)],
        ];
    }
}
