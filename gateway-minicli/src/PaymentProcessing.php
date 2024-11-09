<?php

namespace App;

use App\Helper\PaymentOperationPayload;
use App\Infrastructure\Gateway\PaymentGatewayFactory;
use App\Service\Cancel\Data\CancelData;
use App\Service\Capture\Data\CaptureData;
use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;

class PaymentProcessing
{
    public function __construct(private readonly string $gatewayName) {}

    public function charge(): PaymentOperationResult
    {
        $payload = PaymentOperationPayload::charge();

        $transactionData = ChargeTransactionData::fromPayload($payload);
        $cardData = ChargeCardData::fromPayload($payload);
        $service = PaymentGatewayFactory::charge($this->gatewayName);

        return $service->charge($transactionData, $cardData);
    }

    public function capture(): PaymentOperationResult
    {
        $payload = PaymentOperationPayload::capture();

        $captureData = CaptureData::fromPayload($payload);
        $service = PaymentGatewayFactory::capture($this->gatewayName);

        return $service->capture($captureData);
    }

    public function cancel(): PaymentOperationResult
    {
        $payload = PaymentOperationPayload::cancel();

        $cancelData = CancelData::fromPayload($payload);
        $service = PaymentGatewayFactory::cancel($this->gatewayName);

        return $service->cancel($cancelData);
    }

    public function refund(): PaymentOperationResult
    {
        $payload = PaymentOperationPayload::refund();

        $refundData = RefundData::fromPayload($payload);
        $service = PaymentGatewayFactory::refund($this->gatewayName);

        return $service->refund($refundData);
    }

    private function retrievePayload(): array
    {
        return json_decode($this->getFakeJsonData(), true);
    }

    private function getFakeJsonData(): string
    {
        $cardNumber = random_int(1111111111111111, 9999999999999999);
        return <<<JSON
        {
            "transaction": {
                "type": "credit",
                "amount": 10.00,
                "installments": 1,
                "auto_capture": true
            },
            "card": {
                "number": "$cardNumber",
                "holder": "Fake Customer",
                "expiry_month": "12",
                "expiry_year": "2030",
                "cvv": "123"
            }
        }
        JSON;
    }
}
