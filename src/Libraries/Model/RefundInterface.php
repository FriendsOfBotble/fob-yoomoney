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

use FriendsOfBotble\Yoomoney\Libraries\Model\Deal\RefundDealInfo;

/**
 * Interface RefundInterface
 *
 * @package YooKassa
 *
 * @property-read string $id Идентификатор возврата платежа
 * @property-read string $paymentId Идентификатор платежа
 * @property-read string $payment_id Идентификатор платежа
 * @property-read string $status Статус возврата
 * @property-read \DateTime $createdAt Время создания возврата
 * @property-read \DateTime $create_at Время создания возврата
 * @property-read AmountInterface $amount Сумма возврата
 * @property-read string $receiptRegistration Статус регистрации чека
 * @property-read string $receipt_registration Статус регистрации чека
 * @property-read string $description Комментарий, основание для возврата средств покупателю
 * @property-read RefundDealInfo $deal Данные о сделке, в составе которой проходит возврат
 */
interface RefundInterface
{
    /**
     * Возвращает идентификатор возврата платежа
     * @return string Идентификатор возврата
     */
    public function getId();

    /**
     * Возвращает идентификатор платежа
     * @return string Идентификатор платежа
     */
    public function getPaymentId();

    /**
     * Возвращает статус текущего возврата
     * @return string Статус возврата
     */
    public function getStatus();

    /**
     * Возвращает дату создания возврата
     * @return \DateTime Время создания возврата
     */
    public function getCreatedAt();

    /**
     * Возвращает сумму возврата
     * @return AmountInterface Сумма возврата
     */
    public function getAmount();

    /**
     * Возвращает статус регистрации чека
     * @return string Статус регистрации чека
     */
    public function getReceiptRegistration();

    /**
     * Возвращает комментарий к возврату
     * @return string Комментарий, основание для возврата средств покупателю
     */
    public function getDescription();

    /**
     * Возвращает информацию о распределении денег — сколько и в какой магазин нужно перевести
     * @return SourceInterface[]
     */
    public function getSources();

    /**
     * Возвращает сделку, в рамках которой нужно провести возврат.
     *
     * @return RefundDealInfo Сделка, в рамках которой нужно провести возврат
     */
    public function getDeal();
}
