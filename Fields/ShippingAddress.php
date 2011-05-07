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
final class ShippingAddress implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('SHIPTONAME', 'SHIPTOSTREET',
		'SHIPTOSTREET2', 'SHIPTOCITY', 'SHIPTOSTATE', 'SHIPTOZIP',
		'SHIPTOCOUNTRYCODE', 'SHIPTOPHONENUM', 'ADDRESSSTATUS');

    private function __construct() { }

    /**
     * @return ShippingAddress to be used as request
     */
    public static function getRequest() {

        $address = new self();
        $address->collection = new Collection(self::$allowedValues, null);
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
	 * Person's name associated with this shipping address.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->collection->getValue('SHIPTONAME');
	}

	/**
	 * Person's name associated with this shipping address. Required if
	 * using a shipping address. Character length and limitations: 32
	 * single-byte characters.
	 *
	 * @param String $name
	 */
	public function setName($name) {
		$this->collection->setValue('SHIPTONAME', $name);
	}

	/**
	 * First street address.
	 *
	 * @return string
	 */
	public function getStreet() {
		return $this->collection->getValue('SHIPTOSTREET');
	}

	/**
	 * First street address. Required if using a shipping address.
	 * Character length and limitations: 100 single-byte characters.
	 *
	 * @param String $street
	 */
	public function setStreet($street) {
		$this->collection->setValue('SHIPTOSTREET', $street);
	}

	/**
	 * Second street address.
	 *
	 * @return string
	 */
	public function getStreet2() {
		return $this->collection->getValue('SHIPTOSTREET2');
	}

	/**
	 * Second street address.
	 * Character length and limitations: 100 single-byte characters.
	 *
	 * @param String $street
	 */
	public function setStreet2($street) {
		$this->collection->setValue('SHIPTOSTREET2', $street);
	}

	/**
	 * Name of city.
	 *
	 * @return string
	 */
	public function getCity() {
		return $this->collection->getValue('SHIPTOCITY');
	}

	/**
	 * Name of city. Required if using a shipping address.
	 * Character length and limitations: 40 single-byte characters.
	 *
	 * @param String $city
	 */
	public function setCity($city) {
		$this->collection->setValue('SHIPTOCITY', $city);
	}

	/**
	 * State or province.
	 *
	 * @return string
	 */
	public function getState() {
		return $this->collection->getValue('SHIPTOSTATE');
	}

	/**
	 * State or province. Required if using a shipping address.
	 * Character length and limitations: 40 single-byte characters.
	 *
	 * @param String $state
	 */
	public function setState($state) {
		$this->collection->setValue('SHIPTOSTATE', $state);
	}

	/**
	 * U.S. ZIP code or other country-specific postal code.
	 *
	 * @return string
	 */
	public function getZip() {
		return $this->collection->getValue('SHIPTOZIP');
	}

	/**
	 * U.S. ZIP code or other country-specific postal code. Required if
	 * using a U.S. shipping address; may be required for other countries.
	 * Character length and limitations: 20 single-byte characters.
	 *
	 * @param String $zip
	 */
	public function setZip($zip) {
		$this->collection->setValue('SHIPTOZIP', $zip);
	}

	/**
	 * @return Country
	 */
	public function getCountry() {
		return $this->collection->getValue('SHIPTOCOUNTRYCODE');
	}

	/**
	 * Required if using a shipping address.
	 *
	 * @param Country $country
	 */
	public function setCountry(Country $country) {
		$this->collection->setValue('SHIPTOCOUNTRYCODE', $country->getCode());
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
	 * Status of street address on file with PayPal.
	 * Valid values are:
	 * none, Confirmed, or Unconfirmed
	 *
	 * @return string
	 */
	public function getAddressStatus() {
		return $this->collection->getValue('ADDRESSSTATUS');
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}