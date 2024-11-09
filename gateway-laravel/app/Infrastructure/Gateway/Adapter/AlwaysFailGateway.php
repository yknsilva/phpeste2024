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

class AlwaysFailGateway implements ChargePayment, CapturePayment, CancelPayment, RefundPayment
{
    public function charge(
        ChargeTransactionData $transactionData,
        ChargeCardData $cardData
    ): PaymentOperationResult {
        return PaymentOperationResult::buildError("[Laravel] Charge: you shall not pass!");
    }

    public function capture(CaptureData $captureData): PaymentOperationResult
    {
        return PaymentOperationResult::buildError("[Laravel] Capture: you shall not pass!");
    }

    public function cancel(CancelData $cancelData): PaymentOperationResult
    {
        return PaymentOperationResult::buildError("[Laravel] Cancel: you shall not pass!");
    }

    public function refund(RefundData $refundData): PaymentOperationResult
    {
        return PaymentOperationResult::buildError("[Laravel] Refund: you shall not pass!");
    }
}
