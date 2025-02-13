<?php

/**
 * The MIT License
 *
 * Copyright (c) 2022 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace FriendsOfBotble\Yoomoney\Libraries\Model\Deal;

use FriendsOfBotble\Yoomoney\Libraries\Common\AbstractObject;
use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\EmptyPropertyValueException;
use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\InvalidPropertyValueTypeException;
use FriendsOfBotble\Yoomoney\Libraries\Model\SettlementInterface;

/**
 * Class PaymentDealInfo
 *
 * @package YooKassa
 *
 * @property string $id Идентификатор сделки
 * @property SettlementPayoutPayment[] $settlements Данные о распределении денег
 */
class PaymentDealInfo extends AbstractObject
{
    /** @var string Идентификатор сделки */
    private $_id;

    /** @var SettlementPayoutPayment[] Данные о распределении денег */
    private $_settlements = [];

    /**
     * Возвращает Id сделки
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Устанавливает Id сделки
     *
     * @param string $value Id сделки
     * @return PaymentDealInfo
     */
    public function setId($value)
    {
        $this->_id = $value;

        return $this;
    }

    /**
     * Возвращает массив оплат, обеспечивающих выдачу товара
     *
     * @return SettlementInterface[] Массив оплат, обеспечивающих выдачу товара.
     */
    public function getSettlements()
    {
        return $this->_settlements;
    }

    /**
     * Возвращает массив оплат, обеспечивающих выдачу товара
     *
     * @param SettlementInterface[]|array $value
     */
    public function setSettlements($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty settlements value in deal', 0, 'deal.settlements');
        }
        if (! is_array($value) && ! ($value instanceof \Traversable)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid settlements value type in deal',
                0,
                'deal.settlements',
                $value
            );
        }
        $this->_settlements = [];
        foreach ($value as $key => $val) {
            if (is_array($val)) {
                $this->addSettlement(new SettlementPayoutPayment($val));
            } elseif ($val instanceof SettlementInterface) {
                $this->addSettlement($val);
            } else {
                throw new InvalidPropertyValueTypeException(
                    'Invalid settlements value type in deal',
                    0,
                    'deal.settlements[' . $key . ']',
                    $val
                );
            }
        }
    }

    /**
     * Добавляет оплату в чек
     *
     * @param SettlementInterface $value Объект добавляемой в чек позиции
     */
    public function addSettlement($value)
    {
        $this->_settlements[] = $value;
    }
}
