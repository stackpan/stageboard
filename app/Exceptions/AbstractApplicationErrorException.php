<?php

namespace App\Exceptions;

use Exception;

abstract class AbstractApplicationErrorException extends Exception
{
    public function __construct(string $message = "", ?int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
