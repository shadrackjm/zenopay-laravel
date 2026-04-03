<?php

namespace ShadrackMballah\ZenoPay\Exceptions;

class AuthenticationException extends ZenoPayException
{
    public function __construct(string $message = 'Invalid or missing ZenoPay API key.', ?\Throwable $previous = null)
    {
        parent::__construct($message, '401', null, 401, $previous);
    }
}
