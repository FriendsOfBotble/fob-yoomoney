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

namespace FriendsOfBotble\Yoomoney\Libraries\Model\Payout;

use FriendsOfBotble\Yoomoney\Libraries\Common\AbstractObject;
use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\EmptyPropertyValueException;
use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\InvalidPropertyValueException;
use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\InvalidPropertyValueTypeException;
use FriendsOfBotble\Yoomoney\Libraries\Helpers\TypeCast;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentMethodType;

/**
 * Данные используемые для создания метода оплаты.
 * @property string $type Тип метода оплаты
 */
abstract class AbstractPayoutDestination extends AbstractObject
{
    /**
     * @var string
     */
    private $_type;

    /**
     * Возвращает тип метода оплаты
     * @return string Тип метода оплаты
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает тип метода оплаты
     * @param string $value Тип метода оплаты
     */
    protected function _setType($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty PayoutDestinationData data type',
                0,
                'PayoutDestinationData.type'
            );
        } elseif (TypeCast::canCastToEnumString($value)) {
            if (PaymentMethodType::valueExists($value)) {
                $this->_type = (string)$value;
            } else {
                throw new InvalidPropertyValueException(
                    'Invalid value for "type" parameter in PayoutDestinationData',
                    0,
                    'PayoutDestinationData.type',
                    $value
                );
            }
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "type" parameter in PayoutDestinationData',
                0,
                'PayoutDestinationData.type',
                $value
            );
        }
    }
}
