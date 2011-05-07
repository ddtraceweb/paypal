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
require_once __DIR__ . '/../Util/Currency.php';
require_once __DIR__ . '/../Util/Period.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Field,
    PayPalNVP\Util\Currency,
    PayPalNVP\Util\Period;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class BillingPeriod implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('BILLINGPERIOD', 'BILLINGFREQUENCY',
        'TOTALBILLINGCYCLES', 'AMT', 'TRIALBILLINGPERIOD',
        'TRIALBILLINGFREQUENCY', 'TRIALTOTALBILLINGCYCLES', 'TRIALAMT',
        'CURRENCYCODE', 'SHIPPINGAMT', 'TAXAMT');

    private function __construct() { }

    /**
     *
     * @param Period $period Unit for billing during this subscription period.
     *      Note: The combination of BillingPeriod and BillingFrequency cannot
     *      exceed one year.
     * @param int $frequency Number of billing periods that make up one billing
     *      cycle. The combination of billing frequency and billing period must
     *      be less than or equal to one year. For example, if the billing
     *      cycle is Month, the maximum value for billing frequency is 12.
     *      Similarly, if the billing cycle is Week, the maximum value for
     *      billing frequency is 52.
     *      Note: If the billing period is SemiMonth., the billing frequency
     *      must be 1.
     * @param string $amount Billing amount for each billing cycle during this
     *      payment period. This amount does not include shipping and tax
     *      amounts. All amounts in the CreateRecurringPaymentsProfile request
     *      must have the same currency. Character length and limitations:
     *      Does not exceed $10,000 USD in any currency. No currency symbol.
     *      Regardless of currency, decimal separator is a period (.), and the
     *      optional thousands separator is a comma (,). Equivalent to nine
     *      characters maximum for USD.
     * @return BillingPeriod
     */
    public static function getRequest(Period $period, $frequency, $amount) {

        $billing = new self();
        $billing->collection = new Collection(self::$allowedValues, null);
        $billing->collection->setValue('BILLINGPERIOD', $period->getValue());
        $billing->collection->setValue('BILLINGFREQUENCY', $frequency);
        $billing->collection->setValue('AMT', $amount);

        return $billing;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return BillingPeriod as response
     */
    public static function getResponse(array $response) {

        $billing = new self();
        $billing->collection = new Collection(self::$allowedValues, $response);
        return $billing;
    }

    /**
     * Converts returned value (period) to period object
     *
     * @param string $key response value
     * @return Period
     */
    private function getPeriod($key) {

        if ($key == null) {
            return null;
        }

        switch ($period) {
            case 'Day':
                return Period::getDay();
                break;
            case 'Week':
                return Period::getWeek();
                break;
            case 'SemiMonth':
                return Period::getSemiMonth();
                break;
            case 'Month':
                return Period::getMonth();
                break;
            case 'Year':
                return Period::getYear();
                break;
        }

        return null;
    }

	/**
	 * @return Period
	 */
	public function getBillingPeriod() {
        return $this->getPeriod($this->collection->getValue('BILLINGPERIOD'));
	}

	/**
	 * @return int
	 */
	public function getFrequency() {
        return $this->collection->getValue('BILLINGFREQUENCY');
    }

	/**
	 * @return string
	 */
	public function getAmount() {
        return $this->collection->getValue('AMT');
    }

    /**
     * Default: USD
     *
     * @param Currency $currency
     */
	public function setCurrency(Currency $currency) {
        $this->collection->setValue('CURRENCYCODE', $currency->getCode());
    }

    // TODO - return currency instead of string
    /**
     * @return Currency
     */
	public function getCurrency() {
        return $this->collection->getValue('CURRENCYCODE');
    }

    /**
     * The number of billing cycles for payment period.
     * * For the regular payment period, if no value is specified or the value
     *      is 0, the regular payment period continues until the profile is
     *      canceled or deactivated.
     * * For the regular payment period, if the value is greater than 0, the
     *      regular payment period will expire after the trial period is
     *      finished and continue at the billing frequency for
     *      TotalBillingCycles cycles.
     * @param int $cycles
     */
	public function setTotalBillingCycles($cycles) {
        $this->collection->setValue('TOTALBILLINGCYCLES', $cycles);
    }

    /**
     * The number of billing cycles for payment period.
     *
     * @return int
     */
	public function getTotalBillingCycles() {
        return $this->collection->getValue('TOTALBILLINGCYCLES');
    }

    /**
     * Unit for billing during this subscription period; required if you
     * specify an optional trial period. Note: The combination of BillingPeriod
     * and BillingFrequency cannot exceed one year.
     *
     * @param Period $period
     */
	public function setTrialPeriod(Period $period) {
        $this->collection->setValue('TRIALBILLINGPERIOD', $period->getValue());
    }

    /**
     * Unit for billing during this subscription period; required if you
     * specify an optional trial period. Note: The combination of BillingPeriod
     * and BillingFrequency cannot exceed one year.
     *
     * @return Period
     */
	public function getTrialPeriod() {
        return $this->getPeriod($this->collection->getValue('TRIALBILLINGPERIOD'));
    }

    /**
     * Number of billing periods that make up one billing cycle; required if
     * you specify an optional trial period. The combination of billing
     * frequency and billing period must be less than or equal to one year.
     * For example, if the billing cycle is Month, the maximum value for
     * billing frequency is 12. Similarly, if the billing cycle is Week, the
     * maximum value for billing frequency is 52. Note: If the billing period
     * is SemiMonth., the billing frequency must be 1.
     *
     * @param int $frequency
     */
	public function setTrialFrequency($frequency) {
        $this->collection->setValue('TRIALBILLINGFREQUENCY', $frequency);
    }

    /**
     * Number of billing periods that make up one billing cycle.
     *
     * @return int
     */
	public function getTrialFrequency() {
        return $this->collection->getValue('TRIALBILLINGFREQUENCY');
    }

    /**
     * The number of billing cycles for trial payment period.
     *
     * @param int $cycles
     */
	public function setTrialCycles($cycles) {
        $this->collection->setValue('TRIALTOTALBILLINGCYCLES', $cycles);
    }

    /**
     * The number of billing cycles for trial payment period.
     *
     * @return int
     */
	public function getTrialCycles() {
        return $this->collection->getValue('TRIALTOTALBILLINGCYCLES');
    }

    /**
     * Billing amount for each billing cycle during this payment period;
     * required if you specify an optional trial period. This amount does not
     * include shipping and tax amounts. Note: All amounts in the
     * CreateRecurringPaymentsProfile request must have the same currency.
     * Character length and limitations: Does not exceed $10,000 USD in any
     * currency. No currency symbol. Regardless of currency, decimal separator
     * is a period (.), and the optional thousands separator is a comma (,).
     * Equivalent to nine characters maximum for USD.
     *
     * @param string $amount
     */
	public function setTrialAmount($amount) {
        $this->collection->setValue('TRIALAMT', $amount);
    }

    /**
     * Billing amount for each billing cycle during this payment period;
     *
     * @return string
     */
	public function getTrialAmount() {
        return $this->collection->getValue('TRIALAMT');
    }

    /**
     * Shipping amount for each billing cycle during this payment period.
     * Note: All amounts in the request must have the same currency. Character
     * length and limitations: Does not exceed $10,000 USD in any currency.
     * No currency symbol. Regardless of currency, decimal separator is a
     * period (.), and the optional thousands separator is a comma (,).
     * Equivalent to nine characters maximum for USD.
     *
     * @param string $amount
     */
	public function setShippingAmount($amount) {
        $this->collection->setValue('SHIPPINGAMT', $amount);
    }

    /**
     * Shipping amount for each billing cycle during this payment period.
     *
     * @return string
     */
	public function getShippingAmount() {
        return $this->collection->getValue('SHIPPINGAMT');
    }

    /**
     * Tax amount for each billing cycle during this payment period.
     * Note: All amounts in the request must have the same currency.
     * Character length and limitations: Does not exceed $10,000 USD in any
     * currency. No currency symbol. Regardless of currency, decimal separator
     * is a period (.), and the optional thousands separator is a comma (,).
     * Equivalent to nine characters maximum for USD.
     *
     * @param string $amount
     */
	public function setTaxAmount($amount) {
        $this->collection->setValue('TAXAMT', $amount);
    }

    /**
     * Tax amount for each billing cycle during this payment period.
     *
     * @return string
     */
	public function getTaxAmount() {
        return $this->collection->getValue('TAXAMT');
    }

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}
