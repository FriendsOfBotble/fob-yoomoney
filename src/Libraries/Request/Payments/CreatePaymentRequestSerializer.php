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

namespace FriendsOfBotble\Yoomoney\Libraries\Request\Payments;

use FriendsOfBotble\Yoomoney\Libraries\Model\AmountInterface;
use FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationAttributes\AbstractConfirmationAttributes;
use FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationAttributes\ConfirmationAttributesRedirect;
use FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationType;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\AbstractPaymentData;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataAlfabank;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataB2bSberbank;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataBankCard;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataGooglePay;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataSberbank;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentData\PaymentDataYooMoney;
use FriendsOfBotble\Yoomoney\Libraries\Model\PaymentMethodType;
use FriendsOfBotble\Yoomoney\Libraries\Model\ReceiptInterface;
use FriendsOfBotble\Yoomoney\Libraries\Model\ReceiptItem;
use FriendsOfBotble\Yoomoney\Libraries\Model\TransferInterface;

/**
 * Класс сериалайзера объекта запроса к API на проведение платежа
 *
 * @package YooKassa
 */
class CreatePaymentRequestSerializer
{
    private static $propertyMap = [
        'reference_id' => 'referenceId',
        'payment_token' => 'paymentToken',
        'payment_method_id' => 'paymentMethodId',
        'client_ip' => 'clientIp',
        'merchant_customer_id' => 'merchantCustomerId',
    ];

    private static $paymentDataSerializerMap = [
        PaymentMethodType::BANK_CARD => 'serializePaymentDataBankCard',
        PaymentMethodType::YOO_MONEY => 'serializePaymentDataYooMoney',
        PaymentMethodType::APPLE_PAY => 'serializePaymentDataMobile',
        PaymentMethodType::GOOGLE_PAY => 'serializePaymentDataGooglePay',
        PaymentMethodType::SBERBANK => 'serializePaymentDataSberbank',
        PaymentMethodType::ALFABANK => 'serializePaymentDataAlfabank',
        PaymentMethodType::WEBMONEY => 'serializePaymentData',
        PaymentMethodType::QIWI => 'serializePaymentDataMobilePhone',
        PaymentMethodType::CASH => 'serializePaymentDataMobilePhone',
        PaymentMethodType::MOBILE_BALANCE => 'serializePaymentDataMobilePhone',
        PaymentMethodType::INSTALLMENTS => 'serializePaymentData',
        PaymentMethodType::B2B_SBERBANK => 'serializePaymentDataB2BSberbank',
        PaymentMethodType::TINKOFF_BANK => 'serializePaymentData',
        PaymentMethodType::WECHAT => 'serializePaymentData',
        PaymentMethodType::SBP => 'serializePaymentData',
    ];

    /**
     * Формирует ассоциативный массив данных из объекта запроса
     *
     * @param CreatePaymentRequestInterface $request Объект запроса
     * @return array Массив данных для дальнейшего кодирования в JSON
     */
    public function serialize(CreatePaymentRequestInterface $request)
    {
        $result = [];

        if ($request->getAmount()->getValue() > 0) {
            $result['amount'] = $this->serializeAmount($request->getAmount());
        }

        if ($request->hasTransfers()) {
            $result['transfers'] = $this->serializeTransfers($request->getTransfers());
        }

        if ($request->hasDescription()) {
            $result['description'] = $request->getDescription();
        }
        if ($request->hasReceipt()) {
            $receipt = $request->getReceipt();
            if ($receipt->notEmpty()) {
                $result['receipt'] = $this->serializeReceipt($receipt);
            }
        }
        if ($request->hasRecipient()) {
            $result['recipient']['account_id'] = $request->getRecipient()->getAccountId();
            $result['recipient']['gateway_id'] = $request->getRecipient()->getGatewayId();
        }
        if ($request->hasPaymentMethodData()) {
            $method                        = self::$paymentDataSerializerMap[$request->getPaymentMethodData()->getType()];
            $result['payment_method_data'] = $this->{$method}($request->getPaymentMethodData());
        }
        if ($request->hasConfirmation()) {
            $confirmation           = $request->getConfirmation();
            $result['confirmation'] = $this->serializeConfirmation($confirmation);
        }
        if ($request->hasMetadata()) {
            $result['metadata'] = $request->getMetadata()->toArray();
        }
        if ($request->hasCapture()) {
            $result['capture'] = $request->getCapture();
        }
        if ($request->hasSavePaymentMethod()) {
            $result['save_payment_method'] = $request->getSavePaymentMethod();
        }
        if ($request->hasAirline()) {
            $airline           = $request->getAirline();
            $result['airline'] = [];

            $ticketNumber = $airline->getTicketNumber();
            if (! empty($ticketNumber)) {
                $result['airline']['ticket_number'] = $ticketNumber;
            }
            $bookingReference = $airline->getBookingReference();
            if (! empty($bookingReference)) {
                $result['airline']['booking_reference'] = $bookingReference;
            }

            foreach ($airline->getPassengers() as $passenger) {
                $result['airline']['passengers'][] = [
                    'first_name' => $passenger->getFirstName(),
                    'last_name' => $passenger->getLastName(),
                ];
            }

            foreach ($airline->getLegs() as $leg) {
                $result['airline']['legs'][] = [
                    'departure_airport' => $leg->getDepartureAirport(),
                    'destination_airport' => $leg->getDestinationAirport(),
                    'departure_date' => $leg->getDepartureDate(),
                ];
            }
        }

        if ($request->hasDeal()) {
            $result['deal'] = $request->getDeal()->toArray();
        }

        foreach (self::$propertyMap as $name => $property) {
            $value = $request->{$property};
            if (! empty($value)) {
                $result[$name] = $value;
            }
        }

        return $result;
    }

    private function serializeConfirmation(AbstractConfirmationAttributes $confirmation)
    {
        $result = [
            'type' => $confirmation->getType(),
        ];
        if ($locale = $confirmation->getLocale()) {
            $result['locale'] = $locale;
        }
        if ($confirmation->getType() === ConfirmationType::REDIRECT) {
            /** @var ConfirmationAttributesRedirect $confirmation */
            if ($confirmation->getEnforce()) {
                $result['enforce'] = $confirmation->getEnforce();
            }
            $result['return_url'] = $confirmation->getReturnUrl();
        }

        return $result;
    }

    private function serializeReceipt(ReceiptInterface $receipt)
    {
        $result = [];

        /** @var ReceiptItem $item */
        foreach ($receipt->getItems() as $item) {
            $result['items'][] = $item->jsonSerialize();
        }

        $customer = $receipt->getCustomer();
        if (! empty($customer)) {
            $result['customer'] = $customer->jsonSerialize();
        }

        $value = $receipt->getTaxSystemCode();
        if (! empty($value)) {
            $result['tax_system_code'] = $value;
        }

        return $result;
    }

    private function serializeAmount(AmountInterface $amount)
    {
        return [
            'value' => $amount->getValue(),
            'currency' => $amount->getCurrency(),
        ];
    }

    private function serializePaymentDataBankCard(PaymentDataBankCard $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];
        if ($paymentData->getCard() !== null) {
            $result['card'] = [
                'cardholder' => $paymentData->getCard()->getCardholder(),
                'expiry_year' => $paymentData->getCard()->getExpiryYear(),
                'expiry_month' => $paymentData->getCard()->getExpiryMonth(),
                'number' => $paymentData->getCard()->getNumber(),
                'csc' => $paymentData->getCard()->getCsc(),
            ];
        }

        return $result;
    }

    private function serializePaymentDataYooMoney(PaymentDataYooMoney $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];

        return $result;
    }

    private function serializePaymentDataMobile(AbstractPaymentData $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];
        if ($paymentData->getPaymentData() !== null) {
            $result['payment_data'] = $paymentData->getPaymentData();
        }

        return $result;
    }

    private function serializePaymentDataSberbank(PaymentDataSberbank $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];
        if ($paymentData->getPhone() !== null) {
            $result['phone'] = $paymentData->getPhone();
        }

        return $result;
    }

    private function serializePaymentDataAlfabank(PaymentDataAlfabank $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];
        if ($paymentData->getLogin() !== null) {
            $result['login'] = $paymentData->getLogin();
        }

        return $result;
    }

    private function serializePaymentData(AbstractPaymentData $paymentData)
    {
        return [
            'type' => $paymentData->getType(),
        ];
    }

    private function serializePaymentDataMobilePhone(AbstractPaymentData $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];
        if ($paymentData->getPhone() !== null) {
            $result['phone'] = $paymentData->getPhone();
        }

        return $result;
    }

    /**
     * @param PaymentDataGooglePay $paymentData
     *
     * @return array
     */
    private function serializePaymentDataGooglePay(PaymentDataGooglePay $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
            'payment_method_token' => $paymentData->getPaymentMethodToken(),
            'google_transaction_id' => $paymentData->getGoogleTransactionId(),
        ];

        return $result;
    }

    /**
     * @param PaymentDataB2bSberbank $paymentData
     *
     * @return array
     */
    private function serializePaymentDataB2BSberbank(PaymentDataB2bSberbank $paymentData)
    {
        $result = [
            'type' => $paymentData->getType(),
        ];

        if ($paymentData->getPaymentPurpose() !== null) {
            $result['payment_purpose'] = $paymentData->getPaymentPurpose();
        }

        if ($paymentData->getVatData() !== null) {
            $vatData = [
                'type' => $paymentData->getVatData()->getType(),
            ];

            if ($paymentData->getVatData()->getRate() !== null) {
                $vatData['rate'] = $paymentData->getVatData()->getRate();
            }

            if ($paymentData->getVatData()->getAmount() !== null) {
                $vatData['amount'] = $this->serializeAmount($paymentData->getVatData()->getAmount());
            }

            $result['vat_data'] = $vatData;
        }

        return $result;
    }

    /**
     * @param TransferInterface[] $transfers
     *
     * @return array
     */
    private function serializeTransfers(array $transfers)
    {
        $result = [];
        foreach ($transfers as $transfer) {
            $item = [
                'account_id' => $transfer->getAccountId(),
                'amount' => $this->serializeAmount($transfer->getAmount()),
                'status' => $transfer->getStatus(),
            ];
            if ($transfer->hasPlatformFeeAmount()) {
                $item['platform_fee_amount'] = $this->serializeAmount($transfer->getPlatformFeeAmount());
            }
            if ($transfer->hasMetadata()) {
                $item['metadata'] = $transfer->getMetadata()->toArray();
            }
            $result[] = $item;
        }

        return $result;
    }
}
