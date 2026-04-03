<?php

namespace ShadrackMballah\ZenoPay\Exceptions;

class ValidationException extends ZenoPayException
{
    public function __construct(string $message = 'Invalid request payload.', ?array $responseData = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, '400', $responseData, 400, $previous);
    }
}
