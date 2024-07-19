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

use FriendsOfBotble\Yoomoney\Libraries\Model\ConfirmationType;

/**
 * Class ConfirmationAttributesFactory
 *
 * @package YooKassa
 */
class ConfirmationAttributesFactory
{
    private $typeClassMap = [
        ConfirmationType::CODE_VERIFICATION => 'ConfirmationAttributesCodeVerification',
        ConfirmationType::EXTERNAL => 'ConfirmationAttributesExternal',
        ConfirmationType::REDIRECT => 'ConfirmationAttributesRedirect',
        ConfirmationType::EMBEDDED => 'ConfirmationAttributesEmbedded',
        ConfirmationType::QR => 'ConfirmationAttributesQr',
        ConfirmationType::MOBILE_APPLICATION => 'ConfirmationAttributesMobileApplication',
    ];

    /**
     * @param string $type
     *
     * @return AbstractConfirmationAttributes
     */
    public function factory($type)
    {
        if (! is_string($type)) {
            throw new \InvalidArgumentException('Invalid confirmation attributes value in confirmation factory');
        }
        if (! array_key_exists($type, $this->typeClassMap)) {
            throw new \InvalidArgumentException('Invalid confirmation attributes value type "' . $type . '"');
        }
        $className = __NAMESPACE__ . '\\' . $this->typeClassMap[$type];

        return new $className();
    }

    /**
     * @param array $data
     * @param string|null $type
     *
     * @return AbstractConfirmationAttributes
     */
    public function factoryFromArray(array $data, $type = null)
    {
        if ($type === null) {
            if (array_key_exists('type', $data)) {
                $type = $data['type'];
                unset($data['type']);
            } else {
                throw new \InvalidArgumentException(
                    'Parameter type not specified in ConfirmationAttributesFactory.factoryFromArray()'
                );
            }
        }
        $confirmation = $this->factory($type);
        foreach ($data as $key => $value) {
            if ($confirmation->offsetExists($key)) {
                $confirmation->offsetSet($key, $value);
            }
        }

        return $confirmation;
    }
}
