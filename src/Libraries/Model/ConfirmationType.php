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
 * ConfirmationType - Тип пользовательского процесса подтверждения платежа
 * |Код|Описание|
 * --- | ---
 * |redirect|Необходимо направить плательщика на страницу партнера|
 * |external|Необходимо ождать пока плательщик самостоятельно подтвердит платеж|
 * |code_verification|Необходимо получить одноразовый код от плательщика для подтверждения платежа|
 * |embedded|Необходимо получить токен для checkout.js|
 * |qr|Необходимо получить QR-код|
 * |mobile_application|необходимо совершить действия в мобильном приложении|
 */
class ConfirmationType extends AbstractEnum
{
    public const REDIRECT           = 'redirect';
    public const EXTERNAL           = 'external';
    public const CODE_VERIFICATION  = 'code_verification';
    public const EMBEDDED           = 'embedded';
    public const QR                 = 'qr';
    public const MOBILE_APPLICATION = 'mobile_application';

    protected static $validValues = [
        self::REDIRECT => true,
        self::EXTERNAL => true,
        self::CODE_VERIFICATION => false,
        self::EMBEDDED => true,
        self::QR => true,
        self::MOBILE_APPLICATION => true,
    ];
}
