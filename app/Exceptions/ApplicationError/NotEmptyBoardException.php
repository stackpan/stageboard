<?php

namespace App\Exceptions\ApplicationError;

use App\Exceptions\AbstractApplicationErrorException;

class NotEmptyBoardException extends AbstractApplicationErrorException
{
    public function __construct()
    {
        parent::__construct('The board already has columns', 403);
    }
}
