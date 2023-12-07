<?php

namespace App\Http\Requests;

use App\Models\Column;
use App\Rules\InColumnSize;
use Illuminate\Foundation\Http\FormRequest;

class MoveColumnRequest extends FormRequest
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
        $columnId = $this->route('column');
        $board = Column::findOrFail($columnId)->board;

        return [
            'order' => ['required', 'integer', 'min:0', 'max:10', new InColumnSize($board)],
        ];
    }
}
