<?php

namespace App\Service;

class PaymentOperationResult
{
    const SUCCESS = 'success';
    const ERROR = 'error';

    private function __construct(
        public readonly string $status,
        public readonly string $message,
        public readonly ?string $identifier = null
    ) {}

    public static function buildSuccess(string $message, string $identifier): self
    {
        return new self(self::SUCCESS, $message, $identifier);
    }

    public static function buildError(string $message): self
    {
        return new self(status: self::ERROR, message: $message);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS;
    }
}
