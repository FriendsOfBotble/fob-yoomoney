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

namespace FriendsOfBotble\Yoomoney\Libraries\Request\Refunds;

use FriendsOfBotble\Yoomoney\Libraries\Model\AmountInterface;
use FriendsOfBotble\Yoomoney\Libraries\Model\ReceiptItem;

/**
 * Класс сериалайзера запросов к API на создание нового возврата средств
 *
 * @package YooKassa
 */
class CreateRefundRequestSerializer
{
    /**
     * Сериализует переданный объект запроса к API в массив
     *
     * @param CreateRefundRequestInterface $request Сериализуемый объект запроса
     *
     * @return array Ассоциативный массив для передачи в API
     */
    public function serialize(CreateRefundRequestInterface $request)
    {
        $result = [
            'payment_id' => $request->getPaymentId(),
            'amount' => [
                'value' => $request->getAmount()->getValue(),
                'currency' => $request->getAmount()->getCurrency(),
            ],
        ];
        if ($request->hasDescription()) {
            $result['description'] = $request->getDescription();
        }
        if ($request->hasReceipt()) {
            $receipt           = $request->getReceipt();
            $result['receipt'] = [];
            /** @var ReceiptItem $item */
            foreach ($receipt->getItems() as $item) {
                $itemArray = [
                    'description' => $item->getDescription(),
                    'amount' => [
                        'value' => $item->getPrice()->getValue(),
                        'currency' => $item->getPrice()->getCurrency(),
                    ],
                    'quantity' => $item->getQuantity(),
                    'vat_code' => $item->getVatCode(),
                ];

                if ($item->getPaymentSubject()) {
                    $itemArray['payment_subject'] = $item->getPaymentSubject();
                }

                if ($item->getPaymentMode()) {
                    $itemArray['payment_mode'] = $item->getPaymentMode();
                }

                $result['receipt']['items'][] = $itemArray;
            }

            $value = $receipt->getCustomer()->getEmail();
            if (! empty($value)) {
                $result['receipt']['customer']['email'] = $value;
            }
            $value = $receipt->getCustomer()->getPhone();
            if (! empty($value)) {
                $result['receipt']['customer']['phone'] = $value;
            }
            $value = $receipt->getTaxSystemCode();
            if (! empty($value)) {
                $result['receipt']['tax_system_code'] = $value;
            }
        }

        if ($request->hasSources()) {
            $result['sources'] = $this->serializeSources($request->getSources());
        }

        if ($request->hasDeal()) {
            $result['deal'] = $request->getDeal()->toArray();
        }

        return $result;
    }

    /**
     * @param AmountInterface $amount
     *
     * @return array
     */
    private function serializeAmount(AmountInterface $amount)
    {
        return [
            'value' => $amount->getValue(),
            'currency' => $amount->getCurrency(),
        ];
    }

    /**
     * @param array $sources
     * @return array
     */
    private function serializeSources($sources)
    {
        $result = [];
        foreach ($sources as $source) {
            $result[] = [
                'account_id' => $source->getAccountId(),
                'amount' => $this->serializeAmount($source->getAmount()),
            ];
        }

        return $result;
    }
}
