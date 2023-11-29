<?php

namespace App\Exceptions\ApplicationError;

use App\Exceptions\AbstractApplicationErrorException;

class CrossColumnMemberException extends AbstractApplicationErrorException
{
    public function __construct()
    {
        parent::__construct('Card cannot be moved because the given column is out of board member.');
    }
}