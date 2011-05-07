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
 * @author pete <p.reisinger@gmail.com>
 */
final class Seller implements Field {

    /**
     * @var Collection
     */
    private $collection;

	private static $allowedValues = array('SELLERPAYPALACCOUNTID');

    private function __construct() { }

    /**
     * @return Seller to be used as request
     */
    public static function getRequest() {

        $seller = new self();
        $seller->collection = new Collection(self::$allowedValues, null);
        return $seller;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return Seller as response
     */
    public static function getResponse(array $response) {

        $seller = new self();
        $seller->collection = new Collection(self::$allowedValues, $response);
        return $seller;
    }

	/**
	 * The unique non-changing identifier for the seller at the marketplace
	 * site. This ID is not displayed. Character length and limitations:
	 * 13 single-byte alphanumeric characters
	 *
	 * @param String $id
	 */
	public function setId($id) {
		$this->collection->setValue('SELLERID', $id);
	}

	/**
	 * Unique identifier for the merchant.
	 * For parallel payments, this field contains either the Payer Id or the
	 * email address of the merchant.
	 *
	 * @return string
	 */
	public function getPayPalAccountId() {
        return $this->collection->getValue('SELLERPAYPALACCOUNTID');
	}

	/**
	 * Unique identifier for the merchant. For parallel payments, this
	 * field is required and must contain the Payer Id or the email address
	 * of the merchant. Character length and limitations: 127 single-byte
	 * alphanumeric characters
	 *
	 * @param String $id
	 */
	public function setPayPalAccountId($id) {
		$this->collection->setValue('SELLERPAYPALACCOUNTID', $id);
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}
