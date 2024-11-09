<?php

namespace App\Service\Refund;

use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;

interface RefundPayment
{
    public function refund(RefundData $refundData): PaymentOperationResult;
}
