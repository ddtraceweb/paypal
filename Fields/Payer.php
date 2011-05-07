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
 * Payer Information
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class Payer implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array('EMAIL', 'FIRSTNAME', 'LASTNAME');

    private function __construct() { }

	public static function getResponse(array $response) {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, $response);
	}

    /**
     * @param string $fistName payer's first name
     * @param string $lastName payer's last name
     */
	public static function getRequest($fistName, $lastName) {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, null);
        $info->collection->setValue('FIRSTNAME', $fistName);
        $info->collection->setValue('LASTNAME', $lastName);
	}

	/**
	 * @return string Email address of payer.
	 */
	public function getEmail() {
        return $this->collection->getValue('EMAIL');
	}

	/**
	 * @param string Email address of payer. Character length and limitations:
     *      127 single-byte characters.
	 */
	public function setEmail($email) {
        $this->collection->setValue('EMAIL', $email);
	}

	/**
	 * @return string payer's first name
	 */
	public function getFirstName() {
        return $this->collection->getValue('FIRSTNAME');
	}

	/**
	 * @return string payer's last name
	 */
	public function getLastName() {
        return $this->collection->getValue('LASTNAME');
	}

	public function  getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}
