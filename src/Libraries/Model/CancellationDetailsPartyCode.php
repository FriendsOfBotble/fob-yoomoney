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

namespace FriendsOfBotble\Yoomoney\Libraries\Model;

use FriendsOfBotble\Yoomoney\Libraries\Common\AbstractEnum;

/**
 * CancellationDetailsPartyCode - Возможные инициаторы отмены платежа
 */
class CancellationDetailsPartyCode extends AbstractEnum
{
    /**
     * Продавец товаров и услуг
     */
    public const MERCHANT = 'merchant';

    /**
     * ЮKassa
     */
    public const YOO_KASSA = 'yoo_kassa';

    /**
     * «Внешние» участники платежного процесса (например, эмитент, сторонний платежный сервис)
     */
    public const PAYMENT_NETWORK = 'payment_network';

    protected static $validValues = [
        self::MERCHANT => true,
        self::YOO_KASSA => true,
        self::PAYMENT_NETWORK => true,
    ];
}
