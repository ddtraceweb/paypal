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

namespace PayPalNVP\Response;

require_once 'Response.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/Collection.php';
require_once __DIR__ . '/../Fields/RecurringPaymentsProfile.php';
require_once __DIR__ . '/../Fields/ShippingAddress.php';
require_once __DIR__ . '/../Fields/BillingPeriod.php';
require_once __DIR__ . '/../Fields/RecurringPaymentsSummary.php';
require_once __DIR__ . '/../Fields/CreditCard.php';
require_once __DIR__ . '/../Fields/Payer.php';
require_once __DIR__ . '/../Fields/Address.php';

use PayPalNVP\Response\Response,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\RecurringPaymentsProfile,
    PayPalNVP\Fields\ShippingAddress,
    PayPalNVP\Fields\BillingPeriod,
    PayPalNVP\Fields\RecurringPaymentsSummary,
    PayPalNVP\Fields\CreditCard,
    PayPalNVP\Fields\Payer,
    PayPalNVP\Fields\Address,
    PayPalNVP\Environment;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class GetExpressCheckoutDetailsResponse extends Response {

	/** @var RecurringPaymentsProfile */
	private $recurringPaymentsProfile;

	/** @var ShippingAddress */
	private $shippingAddress;

	/** @var BillingPeriod */
	private $billingPeriod;

	/** @var RecurringPaymentsSummary */
	private $recurringPaymentsSummary;

	/** @var CreditCard */
	private $creditCard;

	/** @var Payer */
	private $payer;

	/** @var Address */
	private $address;

    /**
     * @var Collection
     */
    private $collection;

    private static $allowedValues = array('PROFILEID', 'STATUS', 'DESC', 
        'AUTOBILLOUTAMT', 'MAXFAILEDPAYMENTS', 'AGGREGATEAMOUNT', 
        'AGGREGATEOPTIONALAMOUNT', 'FINALPAYMENTDUEDATE');

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);
        $this->collection = new Collection(self::$allowedValues, $this->getResponse());

        $responseArray = $this->getResponse();
        if (!empty($responseArray)) {
            $this->recurringPaymentsProfile = RecurringPaymentsProfile::getResponse($responseArray);
            $this->shippingAddress = ShippingAddress::getResponse($responseArray);
            $this->billingPeriod = BillingPeriod::getResponse($responseArray);
            $this->recurringPaymentsSummary = RecurringPaymentsSummary::getResponse($responseArray);
            $this->creditCard = CreditCard::getResponse($responseArray);
            $this->payer = Payer::getResponse($responseArray);
            $this->address = Address::getResponse($responseArray);
        }
	}

    /**
     * @return String Recurring payments profile ID returned in the 
     *      CreateRecurringPaymentsProfile response.
     */
	public function getProfileId() {
        return $this->collection->getValue('PROFILEID');
	}

    // TODO - change to enum
    /**
     * @return type Status of the recurring payment profile. 
     *      ActiveProfile 
     *      PendingProfile 
     *      CancelledProfile 
     *      SuspendedProfile 
     *      ExpiredProfile
     */
	public function getStatus() {
        return $this->collection->getValue('STATUS');
	}

    /**
     * @return string Description of the recurring payment.
     */
	public function getDescription() {
        return $this->collection->getValue('DESC');
	}

    /**
     * @return string This field indicates whether you would like PayPal to 
     *      automatically bill the outstanding balance amount in the next 
     *      billing cycle. The outstanding balance is the total amount of any 
     *      previously failed scheduled payments that have yet to be 
     *      successfully paid. Valid values: NoAutoBill or AddToNextBilling.
     */
	public function getAutoBillAmount() {
        return $this->collection->getValue('AUTOBILLOUTAMT');
	}

    /**
     * @return int The number of scheduled payments that can fail before the 
     *      profile is automatically suspended.
     */
	public function getMaxFailedPayments() {
        return $this->collection->getValue('MAXFAILEDPAYMENTS');
	}

    /**
     * @return string total amount collected thus far for scheduled payments.
     */
	public function getAggregateAmount() {
        return $this->collection->getValue('AGGREGATEAMOUNT');
	}

    /**
     * @return string total amount collected thus far for optional payments.
     */
	public function getAggregateOptionalAmount() {
        return $this->collection->getValue('AGGREGATEOPTIONALAMOUNT');
	}

    /**
     * @return string final scheduled payment due date before the profile expires. 
     */
	public function getFinalPaymentDue() {
        return $this->collection->getValue('FINALPAYMENTDUEDATE');
	}

    /**
     * @return RecurringPaymentsProfile 
     */
    public function getRecurringPaymentsProfile() {
        return $this->recurringPaymentsProfile;
    }

    /**
     * @return ShippingAddress 
     */
    public function getShippingAddress() {
        return $this->shippingAddress;
    }

    /**
     * @return BillingPeriod 
     */
    public function getBillingPeriod() {
        return $this->billingPeriod;
    }

    /**
     * @return RecurringPaymentsSummary 
     */
    public function getRecurringPaymentsSummary() {
        return $this->recurringPaymentsSummary;
    }

    /**
     * @return CreditCard 
     */
    public function getCreditCard() {
        return $this->creditCard;
    }

    /**
     * @return Payer 
     */
    public function getPayer() {
        return $this->payer;
    }

    /**
     * @return Address 
     */
    public function getAddress() {
        return $this->address;
    }

	private function  __clone() { }
}