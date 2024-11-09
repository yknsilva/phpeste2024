<?php

namespace App\Service\Capture;

use App\Service\Capture\Data\CaptureData;
use App\Service\PaymentOperationResult;

interface CapturePayment
{
    public function capture(CaptureData $captureData): PaymentOperationResult;
}
