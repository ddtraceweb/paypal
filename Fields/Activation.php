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
final class Activation implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('INITAMT', 'FAILEDINITAMTACTION');

    private function __construct() { }

    /**
     * @return Activation to be used as request
     */
    public static function getRequest() {

        $address = new self();
        $address->collection = new Collection(self::$allowedValues, null);
        return $address;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return Activation as response
     */
    public static function getResponse(array $response) {

        $address = new self();
        $address->collection = new Collection(self::$allowedValues, $response);
        return $address;
    }

	/**
     * Initial non-recurring payment amount due immediately upon profile
     * creation.
     *
	 * @return string
	 */
	public function getInitialAmount() {
		return $this->collection->getValue('INITAMT');
	}

	/**
     * Initial non-recurring payment amount due immediately upon profile
     * creation. Use an initial amount for enrolment or set-up fees.
     * Note: All amounts included in the request must have the same currency.
     * Character length and limitations: Does not exceed $10,000 USD in any
     * currency. No currency symbol. Regardless of currency, decimal separator
     * is a period (.), and the optional thousands separator is a comma (,).
     * Equivalent to nine characters maximum for USD.
	 *
	 * @param String $amount
	 */
	public function setInitialAmount($amount) {
		$this->collection->setValue('INITAMT', $amount);
	}

	/**
	 * @return string
	 */
	public function getFaildInitialAmountAction() {
		return $this->collection->getValue('FAILEDINITAMTACTION');
	}

	/**
     * By default, PayPal will suspend the pending profile in the event that
     * the initial payment amount fails. You can override this default behavior
     * by setting this field to ContinueOnFailure, which indicates that if the
     * initial payment amount fails, PayPal should add the failed payment
     * amount to the outstanding balance for this recurring payment profile.
     * When this flag is set to ContinueOnFailure, a success code will be
     * returned to the merchant in the CreateRecurringPaymentsProfile response
     * and the recurring payments profile will be activated for scheduled
     * billing immediately. You should check your IPN messages or PayPal
     * account for updates of the payment status. If this field is not set or
     * is set to CancelOnFailure, PayPal will create the recurring payment
     * profile, but will place it into a pending status until the initial
     * payment is completed. If the initial payment clears, PayPal will notify
     * you by IPN that the pending profile has been activated. If the payment
     * fails, PayPal will notify you by IPN that the pending profile has been
     * canceled. Character length and limitations: ContinueOnFailure or
     * CancelOnFailure.
	 *
	 * @param String $action
	 */
	public function setFaildInitialAmountAction($action) {
		$this->collection->setValue('FAILEDINITAMTACTION', $action);
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}
