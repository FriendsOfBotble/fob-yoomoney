<?php

namespace FriendsOfBotble\Yoomoney\Libraries\Model\Deal;

use FriendsOfBotble\Yoomoney\Libraries\Common\AbstractEnum;

/**
 * Class FeeMoment
 *
 * @package YooKassa
 */
class FeeMoment extends AbstractEnum
{
    /** Вознаграждение после успешной оплаты */
    public const PAYMENT_SUCCEEDED = 'payment_succeeded';
    /** Вознаграждение при закрытии сделки после успешной выплаты */
    public const DEAL_CLOSED = 'deal_closed';

    protected static $validValues = [
        self::PAYMENT_SUCCEEDED => true,
        self::DEAL_CLOSED => true,
    ];
}
