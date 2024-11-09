<?php

namespace App\Service\Charge\Data;

class ChargeCardData
{
    public function __construct(
        public readonly string $number,
        public readonly string $holder,
        public readonly string $expiry_month,
        public readonly string $expiry_year,
        public readonly string $cvv,
    ) {}

    public static function fromPayload(array $requestPayload): self
    {
        $cardPayload = $requestPayload['card'];
        return new self(
            $cardPayload['number'],
            $cardPayload['holder'],
            $cardPayload['expiry_month'],
            $cardPayload['expiry_year'],
            $cardPayload['cvv'],
        );
    }
}
