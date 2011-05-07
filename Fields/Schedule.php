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
require_once __DIR__ . '/../Util/AutoBill.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Util\AutoBill,
    PayPalNVP\Fields\Field;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class Schedule implements Field {

    /**
     * @var Collection
     */
    private $collection;

    // TODO
    /** @var array values allowed in response */
	private static $allowedValues = array();

    private function __construct() { }

    /**
     * @param String $desc Description of the recurring payment.
     * Note:
     * This field must match the corresponding billing agreement description
     * included in the SetExpressCheckout request. Character length and
     * limitations: 127 single-byte alphanumeric characters
     *
     * @return ScheduleDetails to be used as request
     */
    public static function getRequest($desc) {

        $details = new self();
        $details->collection = new Collection(self::$allowedValues, null);
        $details->collection->setValue('DESC', $desc);
        return $details;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return RecurringPaymentsProfileDetails as response
     */
    public static function getResponse(array $response) {

        $details = new self();
        $details->collection = new Collection(self::$allowedValues, $response);
        return $details;
    }

    /**
     * @return String Description of the recurring payment
     */
	public function getDescription() {
		return $this->collection->getValue('DESC');
	}

    /**
     * @return String The number of scheduled payments that can fail before the
     *      profile is automatically suspended. An IPN message is sent to the
     *      merchant when the specified number of failed payments is reached.
     */
	public function getMaxFailedPayments() {
		return $this->collection->getValue('MAXFAILEDPAYMENTS');
	}

	/**
     * The number of scheduled payments that can fail before the profile is
     * automatically suspended. An IPN message is sent to the merchant when the
     * specified number of failed payments is reached.
     * Character length and limitations: Number string representing an integer.
	 *
	 * @param String $max
	 */
	public function setMaxFailedPayments($max) {
		$this->collection->setValue('MAXFAILEDPAYMENTS', $max);
	}

	/**
     * This field indicates whether you would like PayPal to automatically bill
     * the outstanding balance amount in the next billing cycle. The outstanding
     * balance is the total amount of any previously failed scheduled payments
     * that have yet to be successfully paid.
	 *
	 * @return AutoBill
	 */
	public function getAutoBill() {
		return $this->collection->getValue('AUTOBILLOUTAMT');
	}

	/**
	 * @param String $autoBill This field indicates whether you would like
     *      PayPal to automatically bill the outstanding balance amount in the
     *      next billing cycle. The outstanding balance is the total amount of
     *      any previously failed scheduled payments that have yet to be
     *      successfully paid.
	 */
	public function setAutoBill(AutoBill $autoBill) {
		$this->collection->setValue('AUTOBILLOUTAMT', $autoBill->getValue());
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}

