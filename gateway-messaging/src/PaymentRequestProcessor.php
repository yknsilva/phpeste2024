<?php

namespace App;

use App\Infrastructure\Gateway\PaymentGatewayFactory;
use App\Service\Cancel\Data\CancelData;
use App\Service\Capture\Data\CaptureData;
use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;

class PaymentRequestProcessor
{
    public function __construct(private readonly string $gatewayName) {}

    public function charge(array $payload): PaymentOperationResult
    {
        $transactionData = ChargeTransactionData::fromPayload($payload);
        $cardData = ChargeCardData::fromPayload($payload);
        $service = PaymentGatewayFactory::charge($this->gatewayName);

        return $service->charge($transactionData, $cardData);
    }

    public function capture(array $payload): PaymentOperationResult
    {
        $captureData = CaptureData::fromPayload($payload);
        $service = PaymentGatewayFactory::capture($this->gatewayName);

        return $service->capture($captureData);
    }

    public function cancel(array $payload): PaymentOperationResult
    {
        $cancelData = CancelData::fromPayload($payload);
        $service = PaymentGatewayFactory::cancel($this->gatewayName);

        return $service->cancel($cancelData);
    }

    public function refund(array $payload): PaymentOperationResult
    {
        $refundData = RefundData::fromPayload($payload);
        $service = PaymentGatewayFactory::refund($this->gatewayName);

        return $service->refund($refundData);
    }
}
