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

require_once 'Field.php';
require_once 'Collection.php';

use PayPalNVP\Fields\Field,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class BillingAgreement implements Field {

    /**
     * @var Collection
     */
	private $collection;

    private static $allowedValues = array('BILLINGAGREEMENTDESCRIPTION',
        'BILLINGTYPE', 'PAYMENTTYPE', 'BILLINGAGREEMENTCUSTOM');

	private function  __construct() { }

	/**
	 * Returns new instance of billing agreement
	 *
	 * @param string $description Description of goods or
	 *		services associated with the billing agreement, which is required
	 *		for each recurring payment billing agreement. PayPal recommends
	 *		that the description contain a brief summary of the billing
	 *		agreement terms and conditions. For example, customer will be
	 *		billed at "9.99 per month for 2 years". Character length and
	 *		limitations: 127 single-byte alphanumeric bytes.
	 * @return BillingAgreement
	 */
    public static function getReqeuest($description) {

        $billing = new self();
        $billing->collection = new Collection(self::$allowedValues, null);
        $billing->collection->setValue('BILLINGAGREEMENTDESCRIPTION', $description);
        return $billing;
    }

	/**
	 * Returns new instance of Reucurring payments type of billing agreement
     * Same as calling:
     * $billing = BillingAgreement::getRequest('...');
     * $billing->setBillingType('RecurringPayements');
	 *
	 * @param string $description Description of goods or
	 *		services associated with the billing agreement, which is required
	 *		for each recurring payment billing agreement. PayPal recommends
	 *		that the description contain a brief summary of the billing
	 *		agreement terms and conditions. For example, customer will be
	 *		billed at "9.99 per month for 2 years". Character length and
	 *		limitations: 127 single-byte alphanumeric bytes.
	 * @return BillingAgreement
	 */
	public static function getRecurringPaymentsRequest($description) {

        $billing = new self();
        $billing->collection = new Collection(self::$allowedValues, null);
        $billing->collection->setValue('BILLINGAGREEMENTDESCRIPTION', $description);
        $billing->setBillingType('RecurringPayments');
        return $billing;
	}

    /**
     * Type of billing agreement.
	 * For recurring payments, this field must be set to
	 * <b>RecurringPayments</b> and description (<b>setDescription</b>) MUST be
	 * set as well.
	 * In this case, you can specify up to ten
     * billing agreements. Note: Other defined values are not valid.
     *
     * @param billingType
     */
    private function setBillingType($billingType) {
        $this->collection->setValue('BILLINGTYPE', $billingType);
    }

	/**
	 * Specifies type of PayPal payment you require for the billing
	 * agreement.
	 * - Any
	 * - InstantOnly
	 * Note: For recurring payments, this field is ignored.
	 *
	 * @param PaymentType $paymentType
	 */
	public function setPaymentType(PaymentType $paymentType) {
        $this->collection->setValue('PAYMENTTYPE', $paymentType->getValue());
	}

	/**
	 * Custom annotation field for your own use.
	 * Note: For recurring payments, this field is ignored.
	 * Character length and limitations: 256 single-byte alphanumeric bytes.
	 *
	 * @param String $custom
	 */
	public function setCustomField($custom) {
        $this->collection->setValue('BILLINGAGREEMENTCUSTOM', $custom);
	}

	public function getNVPArray() {
		return $this->collection->getAllValues();
	}

	private function  __clone() { }
}

/**
 * payment type - Any, or InstantOnly
 */
final class PaymentType {

    /**
     * Holds instances for lazy initialization.
     *
     * @var array<PaymentType>
     */
    private static $instances = array();

    private $name;

    /** value for the nvp request */
    private $value;

    private function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     *
     * @return PaymentType
     */
    public static function ANY() {

        $name = 'ANY';
        $value = 'Any';
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name, $value);
        }
        return self::$instances[$name];
    }

    /**
     *
     * @return PaymentType
     */
    public static function INSTANT_ONLY() {

        $name = 'INSTANT_ONLY';
        $value = 'InstantOnly';
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name, $value);
        }
        return self::$instances[$name];
    }

    /**
     * This method is used only by BillingAgreement class
     *
     * @return String   value for the nvp request
     */
    public function getValue() {
        return $this->value;
    }

    public function __toString() {
        return $this->name;
    }

    private function __clone() {}
}
