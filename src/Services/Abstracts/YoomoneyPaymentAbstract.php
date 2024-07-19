<?php

namespace FriendsOfBotble\Yoomoney\Services\Abstracts;

use FriendsOfBotble\Yoomoney\Libraries\Client;
use FriendsOfBotble\Yoomoney\Services\Yoomoney;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Payment\Models\Payment;
use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class YoomoneyPaymentAbstract implements ProduceServiceInterface
{
    use PaymentErrorTrait;

    protected string $paymentCurrency;

    protected Client $client;

    protected bool $supportRefundOnline;

    public function __construct()
    {
        $this->paymentCurrency = config('plugins.payment.payment.currency');

        $this->supportRefundOnline = true;
    }

    public function getSupportRefundOnline(): bool
    {
        return $this->supportRefundOnline;
    }

    public function setCurrency(string $currency): self
    {
        $this->paymentCurrency = $currency;

        return $this;
    }

    public function getPaymentDetails(Payment $payment): array
    {
        try {
            $client = (new Yoomoney())->getClient();

            return $client->getPaymentInfo($payment->charge_id)->toArray();
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return [];
        }
    }

    public function execute(Request $request): mixed
    {
        try {
            return $this->makePayment($request);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }
    }

    abstract public function makePayment(Request $request): mixed;

    abstract public function afterMakePayment(Request $request): mixed;

    public function refundOrder($paymentId, $amount, array $options = []): array
    {
        try {
            $client = (new Yoomoney())->getClient();

            $paymentInfo = $client->getPaymentInfo($paymentId);

            $currencyTitle = $paymentInfo['amount']['currency'];

            $currency = app(CurrencyInterface::class)->getFirstBy(['title' => $currencyTitle]);

            $response = $client->createRefund(
                [
                    'payment_id' => $paymentId,
                    'amount' => [
                        'value' => $amount * get_current_exchange_rate($currency),
                        'currency' => $currencyTitle,
                    ],
                ],
                uniqid('', true)
            );

            $response = $response->toArray();

            if ($response['status'] == 'succeeded') {
                $response = array_merge($response, ['_refund_id' => Arr::get($response, 'id')]);

                return [
                    'error' => false,
                    'message' => $response['status'],
                    'data' => $response,
                ];
            }

            return [
                'error' => true,
                'message' => trans('plugins/payment::payment.status_is_not_completed'),
            ];
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function getRefundDetails(string $refundId): array
    {
        try {
            $client = (new Yoomoney())->getClient();

            $response = $client->getRefundInfo($refundId);

            return [
                'error' => false,
                'message' => $response['status'],
                'data' => $response->toArray(),
                'status' => $response['status'],
            ];
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }
}
