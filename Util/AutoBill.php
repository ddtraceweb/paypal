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
 * This field indicates whether you would like PayPal to automatically bill the
 * outstanding balance amount in the next billing cycle. The outstanding
 * balance is the total amount of any previously failed scheduled payments
 * that have yet to be successfully paid.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class AutoBill {

    private $autoBill;

    private function __construct($autoBill) {
        $this->autoBill = $autoBill;
    }

    /**
     * @return AutoBill
     */
    public static function getNoAutoBill() {
        return new self('NoAutoBill');
    }

    /**
     * @return AutoBill
     */
    public static function getAddToNexBilling() {
        return new self('AddToNextBilling');
    }

    public function getValue() {
        return $this->autoBill;
    }

    private function __clone() {}
}




