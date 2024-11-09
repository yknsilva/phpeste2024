<?php

namespace App\Service\Capture\Data;

class CaptureData
{
    private function __construct(
        public readonly string $reference,
        public readonly float $amount,
    ) {}


    public static function fromPayload(array $requestPayload): self
    {
        return new self(
            $requestPayload['transaction']['identifier'],
            $requestPayload['amount'],
        );
    }
}
