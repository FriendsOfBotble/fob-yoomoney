<?php

namespace FriendsOfBotble\Yoomoney\Http\Controllers;

use FriendsOfBotble\Yoomoney\Services\Yoomoney;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Illuminate\Http\Request;

class YoomoneyController extends BaseController
{
    public function getCallback(Request $request, BaseHttpResponse $response, Yoomoney $yoomoney): BaseHttpResponse
    {
        try {
            $paymentId = session('yoomoney_payment_id');

            if (! $paymentId) {
                abort(404);
            }

            $client = $yoomoney->getClient();

            $data = $client->getPaymentInfo($paymentId);

            if ($data['status'] === 'waiting_for_capture') {
                $data = $client->capturePayment(
                    [
                        'amount' => [
                            'value' => $data['amount']['value'],
                            'currency' => $data['amount']['currency'],
                        ],
                    ],
                    $paymentId,
                    uniqid('', true)
                );
            }

            if ($data['status'] === 'succeeded') {
                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount' => $data['amount']['value'],
                    'currency' => $data['amount']['currency'],
                    'charge_id' => $paymentId,
                    'payment_channel' => YOOMONEY_PAYMENT_METHOD_NAME,
                    'status' => PaymentStatusEnum::COMPLETED,
                    'customer_id' => $request->input('customer_id'),
                    'customer_type' => $request->input('customer_type'),
                    'payment_type' => 'direct',
                    'order_id' => (array)$request->input('order_ids'),
                ], $request);

                session()->forget('yoomoney_payment_id');

                $nextUrl = PaymentHelper::getRedirectURL($request->input('checkout_token'));

                if (is_plugin_active('job-board') || is_plugin_active('real-estate')) {
                    $nextUrl = $nextUrl . '?charge_id=' . $paymentId;
                }

                return $response
                    ->setNextUrl($nextUrl)
                    ->setMessage(__('Checkout successfully!'));
            }

            session()->forget('yoomoney_payment_id');

            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage($data['message'] ?? __('Payment failed!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage($exception->getMessage());
        }
    }
}
