<?php

namespace App\Helper;

class PaymentOperationPayload
{
    public static function charge(): array
    {
        $cardNumber = random_int(1111111111111111, 9999999999999999);
        return [
            'transaction' => [
                'type' => 'credit',
                'amount' => 10.00,
                'installments' => 1,
                'auto_capture' => true,
            ],
            'card' => [
                'number' => "$cardNumber",
                'holder' => 'Fake Customer',
                'expiry_month' => '12',
                'expiry_year' => '2030',
                'cvv' => '123',
            ]
        ];
    }

    public static function capture(): array
    {
        $identifier = uniqid('rabbitmq');
        return [
            'transaction' => [
                'identifier' => "$identifier"
            ],
            'amount' => 10.00
        ];
    }

    public static function cancel(): array
    {
        $identifier = uniqid('rabbitmq');
        return [
            'transaction' => [
                'identifier' => "$identifier"
            ],
        ];
    }

    public static function refund(): array
    {
        $identifier = uniqid('rabbitmq');
        return [
            'transaction' => [
                'identifier' => "$identifier"
            ],
            'amount' => 10.00
        ];
    }
}
