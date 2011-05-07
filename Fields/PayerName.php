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
final class PayerName implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array(
		'SALUTATION', 'FIRSTNAME', 'MIDDLENAME', 'LASTNAME', 'SUFFIX');

	private function __construct() { }

	public static function getResponse(array $response) {

        $payerName = new self();
        $payerName->collection = new Collection(self::$allowedValues, $response);
        return $payerName;
	}

	/**
	 * Payer's salutation.
	 * Character length and limitations: 20 single-byte characters.
	 *
	 * @return string
	 */
	public function getSalutation() {
        return $this->collection->getValue('SALUTATION');
	}

	/**
	 * Payer's first name.
	 * Character length and limitations: 25 single-byte characters.
	 *
	 * @return string
	 */
	public function getFirstName() {
        return $this->collection->getValue('FIRSTNAME');
	}

	/**
	 * Payer's middle name.
	 * Character length and limitations: 25 single-byte characters.
	 *
	 * @return string
	 */
	public function getMiddleName() {
        return $this->collection->getValue('MIDDLENAME');
	}

	/**
	 * Payer's last name.
	 * Character length and limitations: 25 single-byte characters.
	 *
	 * @return string
	 */
	public function getLastName() {
        return $this->collection->getValue('LASTNAME');
	}

	/**
	 * Payer's suffix.
	 * Character length and limitations: 12 single-byte characters.
	 *
	 * @return string
	 */
	public function getSuffix() {
        return $this->collection->getValue('SUFFIX');
	}

	public function getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}