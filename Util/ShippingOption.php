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
 * Determines where or not PayPal displays shipping address fields on the
 * PayPal pages. For digital goods, this field is required. You must set
 * it to NotDisplay.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class ShippingOption {

    private $shippingOption;

    private function __construct($shippingOption) {
        $this->shippingOption = $shippingOption;
    }

    /**
     * PayPal displays the shipping address on the PayPal pages
     * @return ShippingOption
     */
    public static function getDisplay() {
        return new self('0');
    }

    /**
     * PayPal does not display shipping address fields whatsoever
     * @return ShippingOption
     */
    public static function getNoDisplay() {
        return new self('1');
    }

    /**
     * If you do not pass the shipping address, PayPal obtains it from the
     * buyer's account profile
     * @return ShippingOption
     */
    public static function getOrder() {
        return new self('2');
    }

    public function getValue() {
        return $this->shippingOption;
    }

    private function __clone() {}
}
