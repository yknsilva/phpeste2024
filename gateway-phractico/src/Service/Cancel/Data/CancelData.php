<?php

namespace App\Service\Cancel\Data;

class CancelData
{
    private function __construct(
        public readonly string $reference,
    ) {}

    public static function fromPayload(array $requestPayload): self
    {
        return new self(
            $requestPayload['transaction']['identifier'],
        );
    }
}
