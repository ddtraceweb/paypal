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

/**
 * Type of credit card.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class CreditCardType {

    private $creditCardType;

    private function __construct($creditCardType) {
        $this->creditCardType = $creditCardType;
    }

    /**
     * @return CreditCardType
     */
    public static function getVisa() {
        return new self('Visa');
    }

    /**
     * @return CreditCardType
     */
    public static function getMasterCard() {
        return new self('MasterCard');
    }

    /**
     * @return CreditCardType
     */
    public static function getDiscover() {
        return new self('Discover');
    }

    /**
     * @return CreditCardType
     */
    public static function getAmex() {
        return new self('Amex');
    }

    /**
     * Currencycode must be GBP. In addition, either STARTDATE or ISSUENUMBER
     * must be specified.
     *
     * @return CreditCardType
     */
    public static function getMaestro() {
        return new self('Maestro');
    }

    /**
     * Currencycode must be GBP. In addition, either STARTDATE or ISSUENUMBER
     * must be specified.
     *
     * @return CreditCardType
     */
    public static function getSolo() {
        return new self('Solo');
    }

    public function getValue() {
        return $this->creditCardType;
    }

    private function __clone() {}
}





