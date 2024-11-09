<?php

namespace App\Queue;

use App\Bootstrap\RabbitMQ;
use App\Service\PaymentOperationResult;

class PaymentFailedPublisher
{
    public function publish(PaymentOperationResult $result): void
    {
        // Do nothing
    }

    public function getQueue(): string
    {
        return RabbitMQ::QUEUE_PAYMENT_FAILED;
    }
}
