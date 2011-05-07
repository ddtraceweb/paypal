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
require_once __DIR__ . '/../Util/CreditCardType.php';
require_once 'Field.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Util\CreditCardType,
    PayPalNVP\Fields\Field;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class CreditCard implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('INITAMT', 'FAILEDINITAMTACTION');

    private function __construct() { }

    /**
     * @param CreditCardType $type Type of credit card. For UK, only Maestro,
     *      Solo, MasterCard, Discover, and Visa are allowable. For Canada,
     *      only MasterCard and Visa are allowable; Interac debit cards are not
     *      supported. Note: If the credit card type is Maestro or Solo, the
     *      CURRENCYCODE must be GBP. In addition, either STARTDATE or
     *      ISSUENUMBER must be specified.
     * @param string $number Credit card number. Character length and
     *      limitations: numeric characters only. No spaces or punctutation.
     *      Must conform with modulo and length required by each credit card
     *      type.
     * @return CreditCard to be used as request
     */
    public static function getRequest(CreditCardType $type, $number) {

        $card = new self();
        $card->collection = new Collection(self::$allowedValues, null);
        $card->collection->setValue('CREDITCARDTYPE', $type->getValue());
        $card->collection->setValue('ACCT', $number);
        return $card;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return CreditCard as response
     */
    public static function getResponse(array $response) {

        $card = new self();
        $card->collection = new Collection(self::$allowedValues, $response);
        return $card;
    }

    // TODO return credit card type object not string
    /**
     *
     * @return string Type of credit card. For UK, only Maestro,
     *      Solo, MasterCard, Discover, and Visa are allowable. For Canada,
     *      only MasterCard and Visa are allowable; Interac debit cards are not
     *      supported. Note: If the credit card type is Maestro or Solo, the
     *      CURRENCYCODE must be GBP. In addition, either STARTDATE or
     *      ISSUENUMBER must be specified.
     */
	public function getCardType() {
		return $this->collection->getValue('CREDITCARDTYPE');
	}

    /**
     * @return string Credit card number. Character length and
     *      limitations: numeric characters only. No spaces or punctutation.
     *      Must conform with modulo and length required by each credit card
     *      type.
     */
	public function getCardNumber() {
		return $this->collection->getValue('ACCT');
	}

	/**
     * Credit card expiration date.
     *
	 * @return string
	 */
	public function getExpiryDate() {
		return $this->collection->getValue('EXPDATE');
	}

	/**
     * Credit card expiration date. This field is required if you are using
     * recurring payments with direct payments. Format: MMYYYY Character length
     * and limitations: Six single-byte alphanumeric characters, including
     * leading zero.
	 *
	 * @param String $date
	 */
	public function setExpiryDate($date) {
		$this->collection->setValue('EXPDATE', $amount);
	}

	/**
     * Card Verification Value, version 2.
     *
	 * @return string
	 */
	public function getCvv2() {
		return $this->collection->getValue('CVV2');
	}

	/**
     * Card Verification Value, version 2. Your Merchant Account settings
     * determine whether this field is required. Character length for Visa,
     * MasterCard, and Discover: exactly three digits. Character length for
     * American Express: exactly four digits.To comply with credit card
     * processing regulations, you must not store this value after a
     * transaction has been completed.
	 *
	 * @param String $date
	 */
	public function setCvv2($cvv2) {
		$this->collection->setValue('CVV2', $cvv2);
	}

	/**
     * Month and year that Maestro or Solo card was issued, the MMYYYY format.
     * Character length: Must be six digits, including leading zero.
	 *
	 * @param String $date
	 */
	public function setStartDate($date) {
		$this->collection->setValue('STARTDATE', $amount);
	}

	/**
     * Month and year that Maestro or Solo card was issued, the MMYYYY format.
     *
	 * @return string
	 */
	public function getStartDate() {
		return $this->collection->getValue('STARTDATE');
	}

	/**
     * Issue number of Maestro or Solo card. Character length: two numeric
     * digits maximum.
	 *
	 * @param String $date
	 */
	public function setIssueNumber($number) {
		$this->collection->setValue('ISSUENUMBER', $number);
	}

	/**
     * Issue number of Maestro or Solo card.
     *
	 * @return string
	 */
	public function getIssueNumber() {
		return $this->collection->getValue('ISSUENUMBER');
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}

