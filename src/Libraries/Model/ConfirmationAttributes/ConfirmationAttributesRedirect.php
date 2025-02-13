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

namespace FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationAttributes;

use FriendsOfBotble\Yoomoney\Libraries\Common\Exceptions\InvalidPropertyValueTypeException;
use FriendsOfBotble\Yoomoney\Libraries\Helpers\TypeCast;
use FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationType;

/**
 * @property bool $enforce Требование принудительного подтверждения платежа покупателем, требование 3-D Secure для
 * оплаты банковскими картами. По умолчанию определяется политикой платежной системы.
 * @property string $returnUrl URL на который вернется плательщик после подтверждения или отмены платежа на странице партнера.
 * @property string $return_url URL на который вернется плательщик после подтверждения или отмены платежа на странице партнера.
 */
class ConfirmationAttributesRedirect extends AbstractConfirmationAttributes
{
    /**
     * @var bool Требование принудительного подтверждения платежа покупателем, требование 3-D Secure для оплаты
     * банковскими картами. По умолчанию определяется политикой платежной системы.
     */
    private $_enforce;

    /**
     * @var string URL на который вернется плательщик после подтверждения или отмены платежа на странице партнера.
     */
    private $_returnUrl;

    public function __construct()
    {
        $this->_setType(ConfirmationType::REDIRECT);
    }

    /**
     * @return bool Требование принудительного подтверждения платежа покупателем, требование 3-D Secure для
     * оплаты банковскими картами. По умолчанию определяется политикой платежной системы.
     */
    public function getEnforce()
    {
        return $this->_enforce;
    }

    /**
     * @param bool $value Требование принудительного подтверждения платежа покупателем, требование 3-D Secure
     * для оплаты банковскими картами. По умолчанию определяется политикой платежной системы.
     */
    public function setEnforce($value)
    {
        if ($value === null || $value === '') {
            $this->_enforce = null;
        } elseif (TypeCast::canCastToBoolean($value)) {
            $this->_enforce = (bool)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid enforce value type',
                0,
                'confirmationAttributesRedirect.enforce',
                $value
            );
        }
    }

    /**
     * @return string URL на который вернется плательщик после подтверждения или отмены платежа на странице партнера.
     */
    public function getReturnUrl()
    {
        return $this->_returnUrl;
    }

    /**
     * @param string $value URL на который вернется плательщик после подтверждения или отмены платежа
     * на странице партнера.
     */
    public function setReturnUrl($value)
    {
        if ($value === null || $value === '') {
            $this->_returnUrl = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_returnUrl = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid returnUrl value type',
                0,
                'confirmationAttributesRedirect.returnUrl',
                $value
            );
        }
    }
}
