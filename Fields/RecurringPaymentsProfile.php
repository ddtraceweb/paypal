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
final class RecurringPaymentsProfile implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('SUBSCRIBERNAME', 'PROFILESTARTDATE',
		'PROFILEREFERENCE');

    private function __construct() { }

    /**
     * @param String $startDate The date when billing for this profile begins.
     *      Must be a valid date, in UTC/GMT format.
     *      Note: The profile may take up to 24 hours for activation.
     * @return RecurringPaymentsProfile to be used as request
     */
    public static function getRequest($startDate) {

        $details = new self();
        $details->collection = new Collection(self::$allowedValues, null);
        $details->collection->setValue('PROFILESTARTDATE', $startDate);
        return $details;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return RecurringPaymentsProfile as response
     */
    public static function getResponse(array $response) {

        $details = new self();
        $details->collection = new Collection(self::$allowedValues, $response);
        return $details;
    }

    /**
     * @return String The date when billing for this profile begins.
     */
	public function getStartDate() {
		return $this->collection->getValue('PROFILESTARTDATE');
	}

	/**
     * Full name of the person receiving the product or service paid for by the
     * recurring payment. If not present, the name in the buyer's PayPal
     * account is used.
	 *
	 * @return string
	 */
	public function getSubscriberName() {
		return $this->collection->getValue('SUBSCRIBERNAME');
	}

	/**
     * Full name of the person receiving the product or service paid for by the
     * recurring payment. If not present, the name in the buyer's PayPal
     * account is used.
     * Character length and limitations: 32 single-byte characters.
	 *
	 * @param String $name
	 */
	public function setSubscriberName($name) {
		$this->collection->setValue('SUBSCRIBERNAME', $street);
	}

	/**
     * The merchant's own unique reference or invoice number.
     * Character length and limitations: 127 single-byte alphanumeric characters.
	 *
	 * @return string
	 */
	public function getProfileReference() {
		return $this->collection->getValue('PROFILEREFERENCE');
	}

	/**
     * The merchant's own unique reference or invoice number.
	 *
	 * @param String $reference
	 */
	public function setProfileReference($reference) {
		$this->collection->setValue('PROFILEREFERENCE', $reference);
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}
