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

namespace PayPalNVP\Fields;

require_once 'Field.php';

use PayPalNVP\Fields\Field;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class FundingSource implements Field {

    /**
     * @var Collection
     */
	private $collection;

    private static $allowedValues = array('ALLOWPUSHFUNDING');

	private function  __construct() { }

    public static function getReqeuest() {

        $funding = new self();
        $funding->collection = new Collection(self::$allowedValues, null);
        return $funding;
    }

    /**
     * Whether the merchant can accept push funding:
     * true — Merchant can accept push funding
     * false — Merchant cannot accept push funding
     * Note: This field overrides the setting in the merchant's PayPal account
     *
     * @param boolean $pushFunding
     */
    private function setAllowPushFunding($pushFunding) {
        $this->collection->setValue('ALLOWPUSHFUNDING', $pushFunding);
    }

	public function getNVPArray() {
		return $this->collection->getAllValues();
	}

	private function  __clone() { }
}