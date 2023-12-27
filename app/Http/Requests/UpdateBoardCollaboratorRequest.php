<?php

namespace App\Http\Requests;

use App\Enums\BoardPermission;
use App\Rules\InCollaboration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBoardCollaboratorRequest extends FormRequest
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
        $user = $this->user();
        $board = $this->route('board');

        return [
            'userId' => ['required', 'ulid', Rule::notIn([$user->id]), new InCollaboration($board)],
            'permission' => ['required', Rule::enum(BoardPermission::class), Rule::notIn([BoardPermission::FULL_ACCESS->name])]
        ];
    }
}
