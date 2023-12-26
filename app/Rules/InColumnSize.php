<?php

namespace App\Rules;

use App\Models\Board;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class InColumnSize implements ValidationRule
{
    public function __construct(
        private readonly Board $board
    )
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $columnsCount = $this->board->columns()->count();

        if ($value > $columnsCount) {
            $fail('The :attribute is out of available columns.');
        }
    }
}
