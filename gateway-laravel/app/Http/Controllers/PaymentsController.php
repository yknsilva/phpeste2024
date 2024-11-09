<?php

namespace App\Http\Controllers;

use App\Infrastructure\Gateway\PaymentGatewayFactory;
use App\Service\Cancel\Data\CancelData;
use App\Service\Capture\Data\CaptureData;
use App\Service\Charge\Data\ChargeCardData;
use App\Service\Charge\Data\ChargeTransactionData;
use App\Service\PaymentOperationResult;
use App\Service\Refund\Data\RefundData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function charge(Request $request): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload($request);

        $transactionData = ChargeTransactionData::fromPayload($requestPayload);
        $cardData = ChargeCardData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::charge($requestPayload['gateway']);
        $result = $service->charge($transactionData, $cardData);

        return $this->buildJsonResponse($result);
    }

    public function capture(Request $request): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload($request);

        $captureData = CaptureData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::capture($requestPayload['gateway']);
        $result = $service->capture($captureData);

        return $this->buildJsonResponse($result);
    }

    public function cancel(Request $request): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload($request);

        $cancelData = CancelData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::cancel($requestPayload['gateway']);
        $result = $service->cancel($cancelData);

        return $this->buildJsonResponse($result);
    }

    public function refund(Request $request): JsonResponse
    {
        $requestPayload = $this->retrieveRequestPayload($request);

        $refundData = RefundData::fromPayload($requestPayload);

        $service = PaymentGatewayFactory::refund($requestPayload['gateway']);
        $result = $service->refund($refundData);

        return $this->buildJsonResponse($result);
    }

    private function retrieveRequestPayload(Request $request): array
    {
        return json_decode($request->getContent(), true);
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
            data: $responseBody
        );
    }
}
