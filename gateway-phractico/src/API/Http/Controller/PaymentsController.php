<?php

namespace App\API\Http\Controller;

use App\Infrastructure\Gateway\PaymentGatewayFactory;
use App\Service\Cancel\Data\CancelData;
use App\Service\Capture\Data\CaptureData;
use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;
use Phractico\Core\Infrastructure\Http\Controller;
use Phractico\Core\Infrastructure\Http\Request\RequestHandler;
use Phractico\Core\Infrastructure\Http\Request\Route;
use Phractico\Core\Infrastructure\Http\Request\RouteCollection;
use Phractico\Core\Infrastructure\Http\Response;
use Phractico\Core\Infrastructure\Http\Response\JsonResponse;

class PaymentsController implements Controller
{
    public function routes(): RouteCollection
    {
        $routeCollection = RouteCollection::for($this);
        $routeCollection->add(Route::create('POST', '/api/charge'), 'charge');
        $routeCollection->add(Route::create('POST', '/api/capture'), 'capture');
        $routeCollection->add(Route::create('POST', '/api/cancel'), 'cancel');
        $routeCollection->add(Route::create('POST', '/api/refund'), 'refund');
        return $routeCollection;
    }

    public function charge(): Response
    {
        $requestPayload = $this->retrieveRequestPayload();

        $transactionData = ChargeTransactionData::fromPayload($requestPayload);
        $cardData = ChargeCardData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::charge($requestPayload['gateway']);
        $result = $service->charge($transactionData, $cardData);

        return $this->buildJsonResponse($result);
    }

    public function capture(): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload();

        $captureData = CaptureData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::capture($requestPayload['gateway']);
        $result = $service->capture($captureData);

        return $this->buildJsonResponse($result);
    }

    public function cancel(): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload();

        $cancelData = CancelData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::cancel($requestPayload['gateway']);
        $result = $service->cancel($cancelData);

        return $this->buildJsonResponse($result);
    }

    public function refund(): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload();

        $refundData = RefundData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::refund($requestPayload['gateway']);
        $result = $service->refund($refundData);

        return $this->buildJsonResponse($result);
    }

    private function retrieveRequestPayload(): array
    {
        $incomingRequest = RequestHandler::getIncomingRequest();
        return json_decode($incomingRequest->getBody()->getContents(), true);
    }

    private function buildJsonResponse(PaymentOperationResult $result): JsonResponse
    {
        $wasPaymentOperationSuccessful = $result->isSuccess();
        $responseStatus = $wasPaymentOperationSuccessful ? 200 : 500;
        $responseBody = [
            'status' => $result->status,
            'message' => $result->message,
        ];
        if ($wasPaymentOperationSuccessful) {
            $responseBody['transaction'] = ['identifier' => $result->identifier];
        }
        return new JsonResponse(
            status: $responseStatus,
            body: $responseBody
        );
    }
}
