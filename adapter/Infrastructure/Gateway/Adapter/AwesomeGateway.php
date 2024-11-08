<?php

namespace App\Infrastructure\Gateway\Adapter;

use App\Service\Cancel\CancelPayment;
use App\Service\Cancel\Data\CancelData;
use App\Service\Capture\CapturePayment;
use App\Service\Capture\Data\CaptureData;
use App\Service\Charge\ChargePayment;
use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;
use App\Service\Refund\RefundPayment;

class AwesomeGateway implements ChargePayment, CapturePayment, CancelPayment, RefundPayment
{
    public function charge(
        ChargeTransactionData $transactionData,
        ChargeCardData $cardData
    ): PaymentOperationResult {
        $transactionIdentifier = uniqid('awesome_');
        return PaymentOperationResult::buildSuccess(
            "[Phractico] Awesome Gateway: charge successful",
            $transactionIdentifier
        );
    }

    public function capture(CaptureData $captureData): PaymentOperationResult
    {
        return PaymentOperationResult::buildSuccess(
            "[Phractico] Awesome Gateway: capture successful",
            $captureData->reference
        );
    }

    public function cancel(CancelData $cancelData): PaymentOperationResult
    {
        return PaymentOperationResult::buildSuccess(
            "[Phractico] Awesome Gateway: cancel successful",
            $cancelData->reference
        );
    }

    public function refund(RefundData $refundData): PaymentOperationResult
    {
        return PaymentOperationResult::buildSuccess(
            "[Phractico] Awesome Gateway: refund successful",
            $refundData->reference
        );
    }
}
