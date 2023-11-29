<?php

namespace App\Exceptions\ApplicationError;

use App\Exceptions\AbstractApplicationErrorException;

class ZeroDeltaStepException extends AbstractApplicationErrorException
{
    public function __construct()
    {
        parent::__construct('The column order is same with specified order.');
    }
}