<?php

namespace App\Exceptions\ApplicationError;

use App\Exceptions\AbstractApplicationErrorException;

class AlreadyOfColumnMemberException extends AbstractApplicationErrorException
{
    public function __construct()
    {
        parent::__construct('Card cannot be moved because the card is already the member of the given column.');
    }
}