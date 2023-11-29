<?php

namespace App\Exceptions;

use Exception;

class ZeroDeltaStepException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            message: 'The column order is same with specified order.',
            code: 400,
        );
    }
}