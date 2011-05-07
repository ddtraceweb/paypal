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

namespace PayPalNVP\Request;

require_once 'Request.php';
require_once __DIR__ . '/../Response/UpdateRecurringPaymentsProfileResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/ShippingAddress.php';
require_once __DIR__ . '/../Fields/BillingPeriod.php';
require_once __DIR__ . '/../Fields/CreditCard.php';
require_once __DIR__ . '/../Fields/PayerInformation.php';
require_once __DIR__ . '/../Fields/Address.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\UpdateRecurringPaymentsProfileResponse,
    PayPalNVP\Environment,
    PayPalNVP\Fields\ShippingAddress,
    PayPalNVP\Fields\BillingPeriod,
    PayPalNVP\Fields\CreditCard,
    PayPalNVP\Fields\PayerInformation,
    PayPalNVP\Fields\Address,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class UpdateRecurringPaymentsProfile implements Request {

    /** Method value of this request */
    private static $methodName = 'UpdateRecurringPaymentsProfile';

    /**
     * @var Collection
     */
    private $collection;

	/** @var UpdateRecurringPaymentsProfileResponse */
    private $response;

	/** @var ShippingAddress */
    private $shippingAddress;

	/** @var BillingPeriod */
    private $billingPeriod;

	/** @var CreditCard */
    private $creditCard;

	/** @var PayerInformation */
    private $payerInformation;

	/** @var Address */
    private $address;

    private static $allowedValues = array();

    /**
     * @param String $profileId recurring payments profile ID returned in the 
     *      CreateRecurringPaymentsProfile response. Character length and 
     *      limitations: 14 single-byte alphanumeric characters. 19 character 
     *      profile IDs are supported for compatibility with previous versions 
     *      of the PayPal API.
     */
    public function  __construct($profileId) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('PROFILEID', $profileId);
		$this->nvpResponse = null;
	}

    /**
     * @param String $note the reason for the update to the recurring payments 
     *      profile. This message will be included in the email notification to 
     *      the buyer for the recurring payments profile update. This note can 
     *      also be seen by both you and the buyer on the Status History page 
     *      of the PayPal account.
     */
    public function setNote($note) {
        $this->collection->setValue('NOTE', $note);
    }

    /**
     * @param String $description description of the recurring payment. Character 
     *      length and limitations: 127 single-byte alphanumeric characters.
     */
    public function setDescription($description) {
        $this->collection->setValue('DESC', $description);
    }

    /**
     * @param String $name full name of the person receiving the product or 
     *      service paid for by the recurring payment. If not present, the name 
     *      in the buyer’s PayPal account is used.  Character length and 
     *      limitations: 32 single-byte characters.
     */
    public function setSubscriberName($name) {
        $this->collection->setValue('SUBSCRIBERNAME', $name);
    }

    /**
     *
     * @param String $reference the merchant's own unique reference or invoice 
     *      number. Character length and limitations: 127 single-byte 
     *      alphanumeric characters.
     */
    public function setProfileReference($reference) {
        $this->collection->setValue('PROFILEREFERENCE', $reference);
    }

    /**
     * @param String $cycles the number of additional billing cycles to add to 
     *      this profile.
     */
    public function setAdditionalBillingCycles($cycles) {
        $this->collection->setValue('ADDITIONALBILLINGCYCLES', $cycles);
    }

    /**
     * @param String $amount billing amount for each cycle in the subscription 
     *      period, not including shipping and tax amounts. Note: For recurring 
     *      payments with Express Checkout, the payment amount can be increased 
     *      by no more than 20% every 180 days (starting when the profile is 
     *      created). Character length and limitations: Does not exceed 
     *      $10,000 USD in any currency. No currency symbol. Regardless of 
     *      currency, decimal separator is a period (.), and the optional 
     *      thousands separator is a comma (,). Equivalent to nine characters 
     *      maximum for USD.
     */
    public function setAmount($amount) {
        $this->collection->setValue('AMT', $amount);
    }

    /**
     * @param String $amount shipping amount for each billing cycle during the 
     *      regular payment period. Note: All amounts in the request must have 
     *      the same currency. Character length and limitations: Does not 
     *      exceed $10,000 USD in any currency. No currency symbol. Regardless 
     *      of currency, decimal separator is a period (.), and the optional 
     *      thousands separator is a comma (,). Equivalent to nine characters 
     *      maximum for USD.
     */
    public function setShippingAmount($amount) {
        $this->collection->setValue('SHIPPINGAMT', $amount);
    }

    /**
     * @param String $amount tax amount for each billing cycle during the regular 
     *      payment period. Note: All amounts in the request must have the 
     *      same currency. Character length and limitations: Does not exceed 
     *      $10,000 USD in any currency. No currency symbol. Regardless of 
     *      currency, decimal separator is a period (.), and the optional 
     *      thousands separator is a comma (,). Equivalent to nine characters 
     *      maximum for USD.
     */
    public function setTaxAmount($amount) {
        $this->collection->setValue('TAXAMT', $amount);
    }

    /**
     * @param String $amount the current past due or outstanding amount for this 
     *      profile. You can only decrease the outstanding amount—it cannot be 
     *      increased. Character length and limitations: Does not exceed 
     *      $10,000 USD in any currency. No currency symbol. Regardless of 
     *      currency, decimal separator is a period (.), and the optional 
     *      thousands separator is a comma (,). Equivalent to nine characters 
     *      maximum for USD.
     */
    public function setOutstandingAmount($amount) {
        $this->collection->setValue('OUTSTANDINGAMT', $amount);
    }

    /**
     * @param String $amount this field indicates whether you would like PayPal 
     *      to automatically bill the outstanding balance amount in the next 
     *      billing cycle. Valid values: Must be NoAutoBill or AddToNextBilling.
     */
    public function setAutoBillAmount($amount) {
        $this->collection->setValue('AUTOBILLOUTAMT', $amount);
    }

    /**
     * @param String $number the number of failed payments allowed before the 
     *      profile is automatically suspended. The specified value cannot be 
     *      less than the current number of failed payments for this profile. 
     *      Character length and limitations: Number string representing an 
     *      integer.
     */
    public function setMaxFailedPayments($number) {
        $this->collection->setValue('MAXFAILEDPAYMENTS', $number);
    }

    /**
     * @param String $date the date when billing for this profile begins. Must be 
     *      a valid date, in UTC/GMT format. Note: The profile may take up to 
     *      24 hours for activation.
     */
    public function setProfileStartDate($date) {
        $this->collection->setValue('PROFILESTARTDATE', $date);
    }

    /**
     * @param ShippingAddress $address
     */
    public function setShippingAddress(ShippingAddress $address) {
        $this->shippingAddress = $address;
    }

    /**
     * @param BillingPeriod $period
     */
    public function setBillingPeriod(BillingPeriod $period) {
        $this->billingPeriod = $period;
    }

    /**
     * @param CreditCard $card
     */
    public function setCreditCard(CreditCard $card) {
        $this->creditCard = $card;
    }

    /**
     * @param PayerInformation $info
     */
    public function setPayerInformation(PayerInformation $info) {
        $this->payerInformation = $info;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address) {
        $this->address = $address;
    }

    public function getNVPRequest() {

		$request = $this->collection->getAllValues();

        if ($this->shippingAddress != null) {
            $request = array_merge($request, $this->shippingAddress->getNVPArray());
        }

        if ($this->billingPeriod != null) {
            $request = array_merge($request, $this->billingPeriod->getNVPArray());
        }

        if ($this->creditCard != null) {
            $request = array_merge($request, $this->creditCard->getNVPArray());
        }

        if ($this->payerInformation != null) {
            $request = array_merge($request, $this->payerInformation->getNVPArray());
        }

        if ($this->address != null) {
            $request = array_merge($request, $this->address->getNVPArray());
        }

		return $request;
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {

		$this->response = new UpdateRecurringPaymentsProfileResponse(
                $nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}

