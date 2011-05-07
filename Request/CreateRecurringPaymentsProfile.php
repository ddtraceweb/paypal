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
require_once __DIR__ . '/../Response/CreateRecurringPaymentsProfileResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/RecurringPaymentsProfile.php';
require_once __DIR__ . '/../Fields/Schedule.php';
require_once __DIR__ . '/../Fields/BillingPeriod.php';
require_once __DIR__ . '/../Fields/Activation.php';
require_once __DIR__ . '/../Fields/ShippingAddress.php';
require_once __DIR__ . '/../Fields/CreditCard.php';
require_once __DIR__ . '/../Fields/PayerInformation.php';
require_once __DIR__ . '/../Fields/PayerName.php';
require_once __DIR__ . '/../Fields/Address.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\CreateRecurringPaymentsProfileResponse,
    PayPalNVP\Environment,
    PayPalNVP\Fields\RecurringPaymentsProfile,
    PayPalNVP\Fields\Schedule,
    PayPalNVP\Fields\BillingPeriod,
    PayPalNVP\Fields\Activation,
    PayPalNVP\Fields\ShippingAddress,
    PayPalNVP\Fields\CreditCard,
    PayPalNVP\Fields\PayerInformation,
    PayPalNVP\Fields\PayerName,
    PayPalNVP\Fields\Address,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class CreateRecurringPaymentsProfile implements Request {

    /** Method value of this request */
    private static $methodName = 'CreateRecurringPaymentsProfile';

    /**
     * @var Collection
     */
    private $collection;

	/** @var CreateRecurringPaymentsProfileResponse */
    private $response;

	/** @var RecurringPaymentsProfile */
    private $profile;

	/** @var Schedule */
    private $schedule;

	/** @var BillingPeriod */
    private $billingPeriod;

	/** @var Activation */
    private $activation;

	/** @var ShippingAddress */
    private $shippingAddress;

	/** @var CreditCard */
    private $creditCard;

	/** @var PayerInformation */
    private $payerInformation;

	/** @var PayerName */
    private $payerName;

	/** @var Address */
    private $address;

    private static $allowedValues = array('TOKEN');

    /**
     * @param RecurringPaymentsProfile $profile
     * @param Schedule $schedule
     * @param BillingPeriod $billingPeriod
     */
    public function  __construct(RecurringPaymentsProfile $profile,
            Schedule $schedule, BillingPeriod $billingPeriod) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->nvpResponse = null;

        $this->profile = $profile;
        $this->schedule = $schedule;
        $this->billingPeriod = $billingPeriod;
	}

	/**
     * A timestamped token, the value of which was returned in the response to
     * the first call to SetExpressCheckout. You can also use the token
     * returned in the SetCustomerBillingAgreement response.
     * Either this token or a credit card number is required. If you include
     * both token and credit card number, the token is used and credit card
     * number is ignored.
     * Call CreateRecurringPaymentsProfile once for each billing agreement
     * included in SetExpressCheckout request and use the same token for each
     * call. Each CreateRecurringPaymentsProfile request creates a single
     * recurring payments profile.
     * Note: Tokens expire after approximately 3 hours.
	 *
     * @param string $token
	 */
	public function setToken($token) {
        $this->collection->setValue('TOKEN', $token);
	}

    /**
     * @param Activation $activation
     */
    public function setActivation(Activation $activation) {
        $this->activation = $activation;
    }

    /**
     * @param ShippingAddress $address
     */
    public function setShippingAddress(ShippingAddress $address) {
        $this->shippingAddress = $address;
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
     * @param PayerName $payerName
     */
    public function setPayerName(PayerName $payerName) {
        $this->payerName = $payerName;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address) {
        $this->address = $address;
    }

    public function getNVPRequest() {

		$request = $this->collection->getAllValues();
        $request = array_merge($request, $this->schedule->getNVPArray(),
                $this->billingPeriod->getNVPArray(), $this->profile->getNVPArray());

        if ($this->activation != null) {
            $request = array_merge($request, $this->activation->getNVPArray());
        }

        if ($this->shippingAddress != null) {
            $request = array_merge($request, $this->shippingAddress->getNVPArray());
        }

        if ($this->creditCard != null) {
            $request = array_merge($request, $this->creditCard->getNVPArray());
        }

        if ($this->payerInformation != null) {
            $request = array_merge($request, $this->payerInformation->getNVPArray());
        }

        if ($this->payerName != null) {
            $request = array_merge($request, $this->payerName->getNVPArray());
        }

        if ($this->address != null) {
            $request = array_merge($request, $this->address->getNVPArray());
        }

		return $request;
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {

		$this->response = new CreateRecurringPaymentsProfileResponse(
                $nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}
