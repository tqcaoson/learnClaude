<?php

namespace App\Exceptions;

use Exception;

class EmailNotVerifiedException extends Exception
{
    public function __construct(string $message = 'Email address has not been verified')
    {
        parent::__construct($message);
    }
}
