<?php

namespace App\Exceptions;

use Exception;

abstract class AbstractApplicationErrorException extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 400);
    }
}