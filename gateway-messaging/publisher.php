<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Bootstrap\Config;
use App\Bootstrap\RabbitMQ;
use App\Helper\PaymentOperationPayload;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = RabbitMQ::EXCHANGE;
$queue = RabbitMQ::QUEUE_PAYMENT_PROCESSING;

$connection = new AMQPStreamConnection(Config::HOST, Config::PORT, Config::USER, Config::PASSWORD);
$channel = $connection->channel();

$channel->queue_declare($queue, durable: true, auto_delete: false);
$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, durable: true, auto_delete: false);
$channel->queue_bind($queue, $exchange);

$queryParams = implode('&', array_slice($argv, 1));
parse_str($queryParams, $params);

$gateway = $params['gateway'];
$operation = $params['operation'];

$messageBody = [
    'gateway' => $gateway,
    'operation' => $operation,
    ...PaymentOperationPayload::$operation()
];

$message = new AMQPMessage(
    json_encode($messageBody),
    [
        'content_type' => 'text/plain',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]
);
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();
