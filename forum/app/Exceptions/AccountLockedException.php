<?php

namespace App\Exceptions;

use Exception;

class AccountLockedException extends Exception
{
    public function __construct(string $message = 'Account is temporarily locked')
    {
        parent::__construct($message);
    }
}
