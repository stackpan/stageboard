<?php

namespace App\Rules;

use App\Models\Board;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InCollaboration implements ValidationRule
{
    public function __construct(
        private readonly Board $board,
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
        $result = $this->board->users()->whereUserId($value)->first();

        if (is_null($result)) {
            $fail('Collaboration is not found. The user is not a collaborator of the board.');
        }
    }
}
