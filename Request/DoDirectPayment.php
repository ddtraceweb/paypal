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
require_once __DIR__ . '/../Response/DoDirectPaymentResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/CreditCard.php';
require_once __DIR__ . '/../Fields/Payer.php';
require_once __DIR__ . '/../Fields/Address.php';
require_once __DIR__ . '/../Fields/Payment.php';
require_once __DIR__ . '/../Fields/Secure3d.php';
require_once __DIR__ . '/../Util/PaymentAction.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\DoDirectPaymentResponse,
    PayPalNVP\Environment,
    PayPalNVP\Fields\CreditCard,
    PayPalNVP\Fields\Payer,
    PayPalNVP\Fields\Address,
    PayPalNVP\Fields\Payment,
    PayPalNVP\Fields\Secure3d,
    PayPalNVP\Util\PaymentAction,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class DoDirectPayment implements Request {


    /** Method value of this request */
    private static $methodName = 'DoDirectPayment';

    /** @var Collection */
    private $collection;

	/** @var DoDirectPaymentResponse */
    private $response;

	/** @var CreditCard */
    private $creditCard;

	/** @var Payer */
    private $payer;

	/** @var Address */
    private $address;

	/** @var Payment */
    private $payment;

    // TODO - ship to address

	/** @var Secure3d */
    private $secure3d;

    private static $allowedValues = array();

    /**
     * @param string $ipAddress IP address of the payer’s browser.
     *      Note: PayPal records this IP addresses as a means to detect
     *      possible fraud.
     *      Character length and limitations: 15 single-byte characters,
     *      including periods, for example: 255.255.255.255.
     * @param CreditCard $creditCard
     * @param Payer $payer
     * @param Address $address
     * @param Payment $payment
     * @param PayerInformation $payerInformation
     */
    public function  __construct($ipAddress, CreditCard $creditCard,
            Payer $payer, Address $address, Payment $payment) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('IPADDRESS', self::$ipAddress);
		$this->nvpResponse = null;

        $this->creditCard = $creditCard;
        $this->payer = $payer;
        $this->address = $address;
        $this->payment = $payment;
	}

    /**
     * @param PaymentAction $action
     */
    public function setPaymentAction(PaymentAction $action) {
		$this->collection->setValue('PAYMENTACTION', $action->getValue());
    }

    /**
     * Flag to indicate whether you want the results returned by Fraud
     * Management Filters. By default, you do not receive this information.
     * true - do not receive FMF details (default)
     * false - receive FMF details
     *
     * @param boolean $return
     */
    public function setReturnFmfDetails($return) {

        $return = ($return) ? 1 : 0;
		$this->collection->setValue('RETURNFMFDETAILS', $return);
    }

    /**
     * @param Secure3d $secure
     */
    public function setSecure3d(Secure3d $secure) {
        $this->secure3d = $secure;
    }

    public function getNVPRequest() {

		$request = $this->collection->getAllValues();

        $request = array_merge($request, $this->creditCard->getNVPArray());
        $request = array_merge($request, $this->payer->getNVPArray());
        $request = array_merge($request, $this->address->getNVPArray());

        foreach($this->payment->getNVPArray() as $key => $value) {

            if (is_array($value)) {
                foreach($value as $itemIndex => $item) {
                    foreach($item as $k => $v) {
                        $request['L_' . $k . $itemIndex] = $v;
                    }
                }
            } else {
                $request[$key] = $value;
            }
        }

        if ($this->secure3d != null) {
            $request = array_merge($request, $this->secure3d->getNVPArray());
        }

		return $request;
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new DoDirectPaymentResponse($nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}

