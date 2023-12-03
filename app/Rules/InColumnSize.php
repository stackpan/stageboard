<?php

namespace App\Rules;

use App\Models\Board;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InColumnSize implements ValidationRule
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
        $columnsCount = Board::findOrFail($this->boardId)->columns()->count();
        
        if ($value > $columnsCount) {
            $fail('The :attribute is out of available columns.');
        }
    }
}
