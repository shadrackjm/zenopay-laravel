<?php

namespace ShadrackMballah\ZenoPay\Exceptions;

use RuntimeException;

class ZenoPayException extends RuntimeException
{
    protected ?string $resultCode;
    protected ?array $responseData;

    public function __construct(string $message, ?string $resultCode = null, ?array $responseData = null, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->resultCode = $resultCode;
        $this->responseData = $responseData;
    }

    public function getResultCode(): ?string
    {
        return $this->resultCode;
    }

    public function getResponseData(): ?array
    {
        return $this->responseData;
    }
}
