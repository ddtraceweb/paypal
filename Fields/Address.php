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
require_once __DIR__ . '/../Util/Country.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Field,
    PayPalNVP\Util\Country;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class Address implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('STREET', 'STREET2',
		'CITY', 'STATE', 'COUNTRYCODE', 'ZIP', 'SHIPTOPHONENUM');

    private function __construct() { }

    /**
	 * @param String $street First street address. Required if using a
     *      shipping address. Character length and limitations: 100 single-byte
     *      characters.
	 * @param String $city Name of city. Required if using a shipping address.
     *      Character length and limitations: 40 single-byte characters.
	 * @param String $state State or province. Required if using a shipping
     *      address. Character length and limitations: 40 single-byte characters.
	 * @param Country $country
     * @return Address to be used as request
     */
    public static function getRequest($street, $city, $state, Country $country) {

        $address = new self();
        $address->collection = new Collection(self::$allowedValues, null);
		$address->collection->setValue('STREET', $street);
		$address->collection->setValue('CITY', $city);
		$address->collection->setValue('STATE', $state);
		$address->collection->setValue('COUNTRYCODE', $country->getCode());
        return $address;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return ShippingAddress as response
     */
    public static function getResponse(array $response) {

        $address = new self();
        $address->collection = new Collection(self::$allowedValues, $response);
        return $address;
    }

	/**
	 * First street address.
	 *
	 * @return string
	 */
	public function getStreet() {
		return $this->collection->getValue('STREET');
	}

	/**
	 * Second street address.
	 *
	 * @return string
	 */
	public function getStreet2() {
		return $this->collection->getValue('STREET2');
	}

	/**
	 * Second street address.
	 * Character length and limitations: 100 single-byte characters.
	 *
	 * @param String $street
	 */
	public function setStreet2($street) {
		$this->collection->setValue('STREET2', $street);
	}

	/**
	 * Name of city.
	 *
	 * @return string
	 */
	public function getCity() {
		return $this->collection->getValue('CITY');
	}

	/**
	 * State or province.
	 *
	 * @return string
	 */
	public function getState() {
		return $this->collection->getValue('STATE');
	}

	/**
	 * U.S. ZIP code or other country-specific postal code.
	 *
	 * @return string
	 */
	public function getZip() {
		return $this->collection->getValue('ZIP');
	}

	/**
	 * U.S. ZIP code or other country-specific postal code. Required if
	 * using a U.S. shipping address; may be required for other countries.
	 * Character length and limitations: 20 single-byte characters.
	 *
	 * @param String $zip
	 */
	public function setZip($zip) {
		$this->collection->setValue('ZIP', $zip);
	}

	/**
	 * @return Country
	 */
	public function getCountry() {
		return $this->collection->getValue('COUNTRYCODE');
	}

	/**
	 * Phone number.
	 *
	 * @return string
	 */
	public function getPhoneNumber() {
		return $this->collection->getValue('SHIPTOPHONENUM');
	}

	/**
	 * Phone number.
	 * Character length and limit: 20 single-byte characters.
	 *
	 * @param String $phoneNumber
	 */
	public function setPhoneNumber($phoneNumber) {
		$this->collection->setValue('SHIPTOPHONENUM', $phoneNumber);
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}