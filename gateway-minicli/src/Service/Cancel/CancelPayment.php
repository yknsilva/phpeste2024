<?php

namespace App\Service\Cancel;

use App\Service\Cancel\Data\CancelData;
use App\Service\PaymentOperationResult;

interface CancelPayment
{
    public function cancel(CancelData $cancelData): PaymentOperationResult;
}
