<?php

namespace FriendsOfBotble\Yoomoney\Services\Gateways;

use FriendsOfBotble\Yoomoney\Libraries\Model\CurrencyCode;
use FriendsOfBotble\Yoomoney\Services\Abstracts\YoomoneyPaymentAbstract;
use Illuminate\Http\Request;

class YoomoneyPaymentService extends YoomoneyPaymentAbstract
{
    public function makePayment(Request $request): mixed
    {
        return null;
    }

    public function afterMakePayment(Request $request): mixed
    {
        return null;
    }

    public function supportedCurrencyCodes(): array
    {
        return CurrencyCode::getValidValues();
    }
}
