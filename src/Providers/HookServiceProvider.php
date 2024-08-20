<?php

namespace FriendsOfBotble\Yoomoney\Providers;

use FriendsOfBotble\Yoomoney\Services\Gateways\YoomoneyPaymentService;
use FriendsOfBotble\Yoomoney\Services\Yoomoney;
use Botble\Ecommerce\Models\Currency as CurrencyEcommerce;
use Botble\JobBoard\Models\Currency as CurrencyJobBoard;
use Botble\RealEstate\Models\Currency as CurrencyRealEstate;
use Botble\Payment\Enums\PaymentMethodEnum;
use Exception;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerYoomoneyMethod'], 19, 2);

        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithYoomoney'], 19, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 93, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['YOOMONEY'] = YOOMONEY_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 32, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == YOOMONEY_PAYMENT_METHOD_NAME) {
                $value = 'Yoomoney';
            }

            return $value;
        }, 32, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == YOOMONEY_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 32, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == YOOMONEY_PAYMENT_METHOD_NAME) {
                $data = YoomoneyPaymentService::class;
            }

            return $data;
        }, 32, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == YOOMONEY_PAYMENT_METHOD_NAME) {
                $paymentService = (new YoomoneyPaymentService());
                $paymentDetail = $paymentService->getPaymentDetails($payment);
                if ($paymentDetail) {
                    $data = view('plugins/yoomoney::detail', ['payment' => $paymentDetail, 'paymentModel' => $payment])->render();
                }
            }

            return $data;
        }, 32, 2);

        add_filter(PAYMENT_FILTER_GET_REFUND_DETAIL, function ($data, $payment, $refundId) {
            if ($payment->payment_channel == YOOMONEY_PAYMENT_METHOD_NAME) {
                $refundDetail = (new YoomoneyPaymentService())->getRefundDetails($refundId);

                if (! Arr::get($refundDetail, 'error')) {
                    $refunds = Arr::get($payment->metadata, 'refunds');
                    $refund = collect($refunds)->firstWhere('id', $refundId);
                    $refund = array_merge($refund, Arr::get($refundDetail, 'data', []));

                    return array_merge($refundDetail, [
                        'view' => view('plugins/yoomoney::refund-detail', ['refund' => $refund, 'paymentModel' => $payment])->render(),
                    ]);
                }

                return $refundDetail;
            }

            return $data;
        }, 32, 3);
    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . view('plugins/yoomoney::settings')->render();
    }

    public function registerYoomoneyMethod(?string $html, array $data): string
    {
        return $html . view('plugins/yoomoney::methods', $data)->render();
    }

    public function checkoutWithYoomoney(array $data, Request $request): array
    {
        if ($data['type'] !== YOOMONEY_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $supportedCurrencies = (new YoomoneyPaymentService())->supportedCurrencyCodes();

        if (! in_array($data['currency'], $supportedCurrencies)) {
            $data['error'] = true;
            $data['message'] = __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => 'Yoomoney', 'currency' => $data['currency'], 'currencies' => implode(', ', $supportedCurrencies)]);

            return $data;
        }

        $currentCurrency = get_application_currency();

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        if (strtoupper($currentCurrency->title) !== 'RUB') {
            $currency = match (true) {
                is_plugin_active('ecommerce') => CurrencyEcommerce::class,
                is_plugin_active('job-board') => CurrencyJobBoard::class,
                is_plugin_active('real-estate') => CurrencyRealEstate::class,
                default => null,
            };

            $supportedCurrency = $currency::query()->where('title', 'RUB')->first();

            if ($supportedCurrency) {
                $paymentData['currency'] = strtoupper($supportedCurrency->title);
                if ($currentCurrency->is_default) {
                    $paymentData['amount'] = $paymentData['amount'] * $supportedCurrency->exchange_rate;
                } else {
                    $paymentData['amount'] = format_price(
                        $paymentData['amount'] / $currentCurrency->exchange_rate,
                        $currentCurrency,
                        true
                    );
                }
            }
        }

        $orderIds = $paymentData['order_id'];

        try {
            $client = (new Yoomoney())->getClient();

            $response = $client->createPayment(
                [
                    'amount' => [
                        'value' => $paymentData['amount'],
                        'currency' => $paymentData['currency'],
                    ],
                    'capture' => true,
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('yoomoney.payment.callback', [
                            'checkout_token' => $paymentData['checkout_token'],
                            'order_ids' => $orderIds,
                            'customer_id' => $paymentData['customer_id'],
                            'customer_type' => $paymentData['customer_type'],
                        ]),
                    ],
                    'description' => $paymentData['description'],
                ],
                uniqid('', true)
            );

            if ($response['status'] === 'pending') {
                session()->put('yoomoney_payment_id', $response['id']);

                $data['checkoutUrl'] = $response['confirmation']['confirmation_url'];

                return $data;
            }

            $data['error'] = true;
            $data['message'] = $response['message'];
        } catch (Exception $exception) {
            $data['error'] = true;
            $data['message'] = json_encode($exception->getMessage());
        }

        return $data;
    }
}
