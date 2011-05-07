<?php
/*
 * Copyright 2011 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PayPalNVP\Util;

use PayPalNVP\Exception\IllegalArgumentException;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class Currency {

    /*
     * This currency is supported as a payment currency and a currency balance
     * for in-country PayPal accounts only.
     */
    private static $inCountry = array('BRL' => true, 'MYR' => true);

    /* Currencies and Currency Codes Supported by PayPal */
    private static $currencyCodes = array(
        'AUD' => 'Australian Dollar',
        'BRL'=> 'Brazilian Real',
        'CAD' => 'Canadian Dollar',
        'CZK' => 'Czech Koruna',
        'DKK' => 'Danish Krone',
        'EUR' => 'Euro',
        'HKD' => 'Hong Kong Dollar',
        'HUF' => 'Hungarian Forint',
        'ILS' => 'Israeli New Sheqel',
        'JPY' => 'Japanese Yen',
        'MYR' => 'Malaysian Ringgit',
        'MXN' => 'Mexican Peso',
        'NOK' => 'Norwegian Krone',
        'NZD' => 'New Zealand Dollar',
        'PHP' => 'Philippine Peso',
        'PLN' => 'Polish Zloty',
        'GBP' => 'Pound Sterling',
        'SGD' => 'Singapore Dollar',
        'SEK' => 'Swedish Krona',
        'CHF' => 'Swiss Franc',
        'TWD' => 'Taiwan New Dollar',
        'THB' => 'Thai Baht',
        'TRY' => 'Turkish Lira',
        'USD ' => 'U.S. Dollar');

    /*
     * Currencies and Currency Codes Supported by Express Checkout and
     * Direct Payment
     */
    private static $ecCurrencies = array(
        'AUD' => true, 'CAD' => true, 'CZK' => true, 'DKK' => true,
        'EUR' => true, 'HUF' => true, 'JPY' => true, 'NOK' => true,
        'NZD' => true, 'PLN' => true, 'GBP' => true, 'SGD' => true,
        'SEK' => true, 'CHF' => true, 'USD' => true);

    private $currencyCode;

    /**
     * use static method getPayPalCurrencies() to obtain currency codes, or
     * getPayPalExpressCheckoutCurrencies() for codes used by Express Checkout
     * and/or Direct Payment
     *
     * @param string $currencyCode - 3 letters
     */
    public function __construct($currencyCode) {

        if (!isset(self::$currencyCodes[$currencyCode])) {
            throw new IllegalArgumentException("Code - $currencyCode is not " .
                    "allowed, use Currency->getPayPalCurrencies() to " .
                    "obtain a list of allowed currencies.");
        }
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return string currency code (3 letters) represented by this instance
     */
    public function getCode() {
        return $this->currencyCode;
    }

    /**
     * @return string currency name
     */
    public function getName() {
        return self::$currencyCodes[$this->currencyCode];
    }

    /**
     * @return boolean  returns true if this currency can be used by Express
     *                  Checkout and/or Direct Payment
     */
    public function isExpressCheckoutCurrency() {
        return isset(self::$ecCurrencies[$this->currencyCode]);
    }

    /**
     * @return boolean  return true if this currency is supported as a payment
     *                  currency and a currency balance for in-country PayPal
     *                  accounts only.
     */
    public function isInCountry() {
        return isset(self::$inCountry[$this->currencyCode]);
    }

    /**
     * Currencies and Currency Codes Supported by PayPal
     * @return array
     */
    public static function getPayPalCurrencies() {
        return self::$currencyCodes;
    }

    /**
     * Currencies and Currency Codes Supported by Express Checkout and
     * Direct Payment
     * @return array
     */
    public static function getPayPalExpressCheckoutCurrencies() {

        $currencies = array();
        foreach(self::$ecCurrencies as $code => $currency) {
            $currencies[$code] = $currency;
        }
        return $currencies;
    }

	private function  __clone() { }
}