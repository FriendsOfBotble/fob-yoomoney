<?php

namespace FriendsOfBotble\Yoomoney\Libraries\Model\Receipt;

use FriendsOfBotble\Yoomoney\Libraries\Common\AbstractEnum;

class AgentType extends AbstractEnum
{
    public const BANKING_PAYMENT_AGENT = 'banking_payment_agent';
    public const BANKING_PAYMENT_SUBAGENT = 'banking_payment_subagent';
    public const PAYMENT_AGENT = 'payment_agent';
    public const PAYMENT_SUBAGENT = 'payment_subagent';
    public const ATTORNEY = 'attorney';
    public const COMMISSIONER = 'commissioner';
    public const AGENT = 'agent';

    protected static $validValues = [
        self::BANKING_PAYMENT_AGENT => true,
        self::BANKING_PAYMENT_SUBAGENT => true,
        self::PAYMENT_AGENT => true,
        self::PAYMENT_SUBAGENT => true,
        self::ATTORNEY => true,
        self::COMMISSIONER => true,
        self::AGENT => true,
    ];
}
