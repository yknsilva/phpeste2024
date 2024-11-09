<?php

namespace App\Bootstrap;

class RabbitMQ
{
    const EXCHANGE = 'payment';
    const QUEUE_PAYMENT_PROCESSING = 'payment_processing';
    const QUEUE_PAYMENT_SUCCESS = 'payment_success';
    const QUEUE_PAYMENT_FAILED = 'payment_failed';
    const CONSUMER_TAG = 'phpeste';
}
