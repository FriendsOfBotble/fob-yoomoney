<?php

namespace FriendsOfBotble\Yoomoney\Services;

use FriendsOfBotble\Yoomoney\Libraries\Client;

class Yoomoney
{
    public function getClient(): Client
    {
        $client = new Client();

        $shopId = get_payment_setting('shop_id', YOOMONEY_PAYMENT_METHOD_NAME);
        $apiSecret = get_payment_setting('api_secret', YOOMONEY_PAYMENT_METHOD_NAME);

        $client->setAuth($shopId, $apiSecret);

        return $client;
    }
}
