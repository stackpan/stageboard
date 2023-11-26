<?php

namespace App\Rules;

use App\Models\Column;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ColumnBoardMember implements ValidationRule
{
    public function __construct(
        private readonly string $boardId,
    )
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $column = Column::select('id')
            ->whereId($value)
            ->whereBoardId($this->boardId)
            ->first();
        
        if (!$column) {
            $fail('The :attribute is out of specified board member.');
        }
    }
}
