<?php

namespace App\Http\Requests\Api;

use App\Rules\ColumnBoardMember;
use Illuminate\Foundation\Http\FormRequest;

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
        
        return [
            'name' => ['required', 'string', 'max:24'],
            'next_column_id' => ['nullable', 'ulid', new ColumnBoardMember($boardId)],
        ];
    }
}
