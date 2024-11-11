# PHPeste 2024 Presentation

## Setup

Install [phpctl](https://github.com/opencodeco/phpctl) and [stack](https://github.com/opencodeco/stack).

## About

Inside [adapter](./adapter/) are the common abstractions for payment processing through different gateways.

Supported gateway names:
- `awesome`: return success for all operations
- `fail`: return error for all operations

Supported operations:
- `charge`
- `capture`
- `cancel`
- `refund`

## Running

### APIs

Use [Postman collection](./Payment_Gateway_API.postman_collection.json) to perform requests.
Both [*phractico*](./gateway-phractico/) and [Laravel](./gateway-laravel/) applications' URLs are defined to `http://localhost:8000/api`.

#### phractico

```
cd gateway-phractico
mkdir database && touch database/database.sqlite
composer install
phpctl server 8000 public
```

#### Laravel

```
cd gateway-laravel
composer install
phpctl server 8000 public
```

### CLI

CLI application uses [minicli](https://github.com/minicli/minicli).

```
cd gateway-minicli
composer install
chmod +x minicli
./minicli payment gateway=<gateway_name> operation=<operation>
```

### Messaging

Messaging application uses [php-amqplib](https://github.com/php-amqplib/php-amqplib).

Start RabbitMQ container:
```
stack rabbitmq
```

Install dependencies:
```
cd gateway-messaging
composer install
```

Publishing messages:
```
php publisher.php gateway=<gateway_name> operation=<operation>
```

Consuming messages:
```
cd gateway-messaging
php consumer.php
```
