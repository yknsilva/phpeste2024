<?php

namespace App\Infrastructure\Gateway;

use App\Infrastructure\Gateway\Adapter\AlwaysFailGateway;
use App\Infrastructure\Gateway\Adapter\AwesomeGateway;
use App\Service\Cancel\CancelPayment;
use App\Service\Capture\CapturePayment;
use App\Service\Charge\ChargePayment;
use App\Service\Refund\RefundPayment;

class PaymentGatewayFactory
{
    /** @throws \InvalidArgumentException */
    public static function charge(string $gateway): ChargePayment
    {
        return self::getService($gateway);
    }

    /** @throws \InvalidArgumentException */
    public static function capture(string $gateway): CapturePayment
    {
        return self::getService($gateway);
    }

    /** @throws \InvalidArgumentException */
    public static function cancel(string $gateway): CancelPayment
    {
        return self::getService($gateway);
    }

    /** @throws \InvalidArgumentException */
    public static function refund(string $gateway): RefundPayment
    {
        return self::getService($gateway);
    }

    private static function getService(string $gateway): ChargePayment|CapturePayment|CancelPayment|RefundPayment
    {
        return match ($gateway) {
            'awesome' => new AwesomeGateway(),
            'fail' => new AlwaysFailGateway(),
            default => throw new \InvalidArgumentException("Gateway $gateway not implemented")
        };
    }
}
