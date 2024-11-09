<?php

namespace App\Helper;

class PaymentOperationPayload
{
    public static function charge(): array
    {
        $cardNumber = random_int(1111111111111111, 9999999999999999);
        $json = <<<JSON
        {
            "transaction": {
                "type": "credit",
                "amount": 10.00,
                "installments": 1,
                "auto_capture": true
            },
            "card": {
                "number": "$cardNumber",
                "holder": "Fake Customer",
                "expiry_month": "12",
                "expiry_year": "2030",
                "cvv": "123"
            }
        }
        JSON;
        return json_decode($json, true);
    }

    public static function capture(): array
    {
        $identifier = uniqid('minicli');
        $json = <<<JSON
        {
            "transaction": {
                "identifier": "$identifier"
            },
            "amount": 10.00
        }
        JSON;
        return json_decode($json, true);
    }

    public static function cancel(): array
    {
        $identifier = uniqid('minicli');
        $json = <<<JSON
        {
            "transaction": {
                "identifier": "$identifier"
            }
        }
        JSON;
        return json_decode($json, true);
    }

    public static function refund(): array
    {
        $identifier = uniqid('minicli');
        $json = <<<JSON
        {
            "transaction": {
                "identifier": "$identifier"
            },
            "amount": 10.00
        }
        JSON;
        return json_decode($json, true);
    }
}
