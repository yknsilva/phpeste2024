<?php

namespace App\Service\Charge;

use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;

interface ChargePayment
{
    public function charge(
        ChargeTransactionData $transactionData,
        ChargeCardData $cardData
    ): PaymentOperationResult;
}
