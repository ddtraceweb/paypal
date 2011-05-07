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

require_once 'Collection.php';
require_once 'Field.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Field;

/**
 * Payer Information Fields for Digital Goods. Payer information is under Payer
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class PayerInformation implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array(
		'EMAIL', 'PAYERID', 'PAYERSTATUS', 'COUNTRYCODE', 'BUSINESS');

    private function __construct() { }

	public static function getResponse(array $response) {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, $response);
	}

	/**
	 * Email address of payer.
	 * Character length and limitations: 127 single-byte characters.
	 *
	 * @return string
	 */
	public function getEmail() {
        return $this->collection->getValue('EMAIL');
	}

	/**
	 * Unique PayPal customer account identification number.
	 * Character length and limitations:13 single-byte alphanumeric characters.
	 *
	 * @return string
	 */
	public function getPayerId() {
        return $this->collection->getValue('PAYERID');
	}

	/**
	 * Status of payer. Valid values are: verified, or unverified
	 *
	 * @return string
	 */
	public function getPayerStatus() {
        return $this->collection->getValue('PAYERSTATUS');
	}

	/**
     * Payer's country of residence in the form of ISO standard 3166
	 * two-character country codes.
	 *
	 * @return string
	 */
	public function getCountryCode() {
        return $this->collection->getValue('COUNTRYCODE');
	}

	/**
     * Payer's business name.
	 *
	 * @return string
	 */
	public function getBussinessName() {
        return $this->collection->getValue('BUSINESS');
	}

	public function  getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}