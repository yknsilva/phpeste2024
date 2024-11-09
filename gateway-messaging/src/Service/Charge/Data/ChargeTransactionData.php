<?php

namespace App\Service\Charge\Data;

class ChargeTransactionData
{
    public function __construct(
        public readonly string $type,
        public readonly string $amount,
        public readonly int $installments,
        public readonly bool $auto_capture,
    ) {}

    public static function fromPayload(array $requestPayload): self
    {
        $transactionPayload = $requestPayload['transaction'];
        return new self(
            $transactionPayload['type'],
            number_format($transactionPayload['amount'], 2, '.', ''),
            (int)$transactionPayload['installments'] ?? 1,
            (bool)$transactionPayload['auto_capture'] ?? false,
        );
    }
}
