<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Bootstrap\Config;
use App\Bootstrap\RabbitMQ;
use App\PaymentRequestProcessor;
use App\Queue\PaymentFailedPublisher;
use App\Queue\PaymentSuccessPublisher;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = RabbitMQ::EXCHANGE;
$queue = RabbitMQ::QUEUE_PAYMENT_PROCESSING;
$consumerTag = RabbitMQ::CONSUMER_TAG;

$connection = new AMQPStreamConnection(Config::HOST, Config::PORT, Config::USER, Config::PASSWORD);
$channel = $connection->channel();

$channel->queue_declare($queue, durable: true, auto_delete: false);
$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, durable: true, auto_delete: false);
$channel->queue_bind($queue, $exchange);

/** @param AMQPMessage $message */
function process_message($message)
{
    $payload = json_decode($message->getBody(), true);
    $gateway = $payload['gateway'];
    $operation = $payload['operation'];

    $processor = new PaymentRequestProcessor($gateway);
    $result = match ($operation) {
        'charge' => $processor->charge($payload),
        'capture' => $processor->capture($payload),
        'cancel' => $processor->cancel($payload),
        'refund' => $processor->refund($payload),
        default => throw new \InvalidArgumentException("Operation '$operation' not supported")
    };

    $message->ack();

    $paymentResultPublisher = $result->isSuccess()
        ? new PaymentSuccessPublisher()
        : new PaymentFailedPublisher();

    echo "Publishing result into '{$paymentResultPublisher->getQueue()}' queue";
    echo PHP_EOL;

    $paymentResultPublisher->publish($result);
}

$channel->basic_consume($queue, $consumerTag, callback: 'process_message');

/**
 * @param AMQPChannel $channel
 * @param AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

echo '--- STARTING CONSUMING MESSAGES ---';
echo PHP_EOL;
$channel->consume();
