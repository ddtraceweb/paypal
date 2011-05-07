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
require_once __DIR__ . '/../Response/GetExpressCheckoutDetailsResponse.php';
require_once __DIR__ . '/../Response/DoExpressCheckoutPaymentResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\GetExpressCheckoutDetailsResponse,
    PayPalNVP\Environment,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Response\DoExpressCheckoutPaymentResponse;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class DoExpressCheckoutPayment implements Request {

    /** Method value of this request */
    private static $methodName = 'DoExpressCheckoutPayment';

    /**
     * @var Collection
     */
    private $collection;

	/**
     * @var UserOptions
     */
	private $userOptions;

	/** @var array<Payment> */
	private $payments = array();

	/** @var DoExpressCheckoutPaymentResponse */
    private $response;

    // BUTTONSOURCE, RETURNFMFDETAILS - not in response
    private static $allowedValues = array('TOKEN', 'PAYERID',
        'RETURNFMFDETAILS', 'GIFTMESSAGE', 'GIFTRECEIPTENABLE', 'GIFTWRAPNAME',
        'GIFTWRAPAMOUNT', 'BUYERMARKETINGEMAIL', 'SURVEYQUESTION',
        'SURVEYCHOICESELECTED', 'BUTTONSOURCE');

    /**
     * If GetExpressCheckoutDetails is passed, then values are populated from
     * the response, otherwise, they need to be set using setters.
     *
     * @param String $queryString from paypal response
     */
    public function  __construct(GetExpressCheckoutDetailsResponse $details = null) {

        $detailsArray = null;
        if ($details != null) {
            $detailsArray = $details->getResponse();
        }

        $this->collection = new Collection(self::$allowedValues, $detailsArray);
		$this->collection->setValue('METHOD', self::$methodName);

        if ($details != null) {
            $this->userOptions = $details->getUserOptions();
            $this->payments = $details->getPayements();
        }
	}

	/**
	 * The timestamped token value that was returned by SetExpressCheckout
	 * response and passed on GetExpressCheckoutDetails request.
	 * Character length and limitations: 20 single-byte characters
	 *
	 * @return string
	 */
	public function getToken() {
        return $this->collection->getValue('TOKEN');
	}

	/**
	 * The timestamped token value that was returned by SetExpressCheckout
	 * response and passed on GetExpressCheckoutDetails request.
	 * Character length and limitations: 20 single-byte characters
	 *
     * @param string $token
	 */
	public function setToken($token) {
        $this->collection->setValue('TOKEN', $token);
	}

	/**
     * Unique PayPal customer account identification number as returned by
     * GetExpressCheckoutDetails response Character length and limitations:
     * 13 single-byte alphanumeric characters
	 *
	 * @return string
	 */
	public function getPayerId() {
        return $this->collection->getValue('PAYERID');
	}

	/**
     * Unique PayPal customer account identification number as returned by
     * GetExpressCheckoutDetails response Character length and limitations:
     * 13 single-byte alphanumeric characters
	 *
     * @param string $id
	 */
	public function setPayerId($id) {
        $this->collection->setValue('PAYERID', $id);
	}

    /**
     * Flag to indicate whether you want the results returned by Fraud
     * Management Filters. By default, you do not receive this information.
     * * 0 - do not receive FMF details (default)
     * * 1 - receive FMF details
     *
     * @return string
     */
	public function getFmfDetails() {
        return $this->collection->getValue('RETURNFMFDETAILS');
	}

    /**
     * Flag to indicate whether you want the results returned by Fraud
     * Management Filters. By default, you do not receive this information.
     * * 0 - do not receive FMF details (default)
     * * 1 - receive FMF details
     *
     * @param string $details
     */
	public function setFmfDetails($details) {
        $this->collection->setValue('RETURNFMFDETAILS', $details);
	}

	/**
	 * The gift message entered by the buyer on the PayPal Review page.
	 * Limitations: 150 single-byte characters
	 *
	 * @return string
	 */
	public function getGiftMessage() {
        return $this->collection->getValue('GIFTMESSAGE');
	}

	/**
	 * The gift message entered by the buyer on the PayPal Review page.
	 * Limitations: 150 single-byte characters
	 *
     * @param string $message
	 */
	public function setGiftMessage() {
        $this->collection->setValue('GIFTMESSAGE', $message);
	}

	/**
	 * Returns true if the buyer requested a gift receipt on the PayPal Review
	 * page and false if the buyer did not.
	 *
	 * @return boolean
	 */
	public function getGiftReceipt() {
        return $this->collection->getValue('GIFTRECEIPTENABLE');
	}

	/**
	 * Returns true if the buyer requested a gift receipt on the PayPal Review
	 * page and false if the buyer did not.
	 *
     * @param string $receipt
	 */
	public function setGiftReceipt($receipt) {
        $this->collection->setValue('GIFTRECEIPTENABLE', $receipt);
	}

	/**
	 * Return the gift wrap name only if the gift option on the PayPal Review
	 * page is selected by the buyer. Limitations: 25 single-byte characters
	 *
	 * @return string
	 */
	public function getGiftWrapName() {
        return $this->collection->getValue('GIFTWRAPNAME');
	}

	/**
	 * Return the gift wrap name only if the gift option on the PayPal Review
	 * page is selected by the buyer. Limitations: 25 single-byte characters
	 *
     * @param string $name
	 */
	public function setGiftWrapName($name) {
        $this->collection->setValue('GIFTWRAPNAME', $name);
	}

	/**
	 * Return the amount only if the gift option on the PayPal Review page is
	 * selected by the buyer.
	 *
	 * @return string
	 */
	public function getGiftWrapAmount() {
        return $this->collection->getValue('GIFTWRAPAMOUNT');
	}

	/**
	 * Return the amount only if the gift option on the PayPal Review page is
	 * selected by the buyer.
	 *
     * @param string $amount
	 */
	public function setGiftWrapAmount($amount) {
        $this->collection->setValue('GIFTWRAPAMOUNT', $amount);
	}

	/**
	 * The buyer email address opted in by the buyer on the PayPal Review page.
	 * Limitations: 127 single-byte characters
	 *
	 * @return string
	 */
	public function getBuyerEmail() {
        return $this->collection->getValue('BUYERMARKETINGEMAIL');
	}

	/**
	 * The buyer email address opted in by the buyer on the PayPal Review page.
	 * Limitations: 127 single-byte characters
	 *
     * @param string $email
	 */
	public function setBuyerEmail($email) {
        $this->collection->setValue('BUYERMARKETINGEMAIL', $email);
	}

	/**
	 * The survey question on the PayPal Review page.
	 * Limitations: 50 single-byte characters
	 *
	 * @return string
	 */
	public function getSurveyQuestion() {
        return $this->collection->getValue('SURVEYQUESTION');
	}

	/**
	 * The survey question on the PayPal Review page.
	 * Limitations: 50 single-byte characters
	 *
     * @param string $question
	 */
	public function setSurveyQuestion($question) {
        $this->collection->setValue('SURVEYQUESTION', $question);
	}

	/**
	 * The survey response selected by the buyer on the PayPal Review page.
	 * Limitations: 15 single-byte characters
	 *
	 * @return string
	 */
	public function getSurveyChoice() {
        return $this->collection->getValue('SURVEYCHOICESELECTED');
	}

	/**
	 * The survey response selected by the buyer on the PayPal Review page.
	 * Limitations: 15 single-byte characters
	 *
     * @param string $choice
	 */
	public function setSurveyChoice($choice) {
        $this->collection->setValue('SURVEYCHOICESELECTED', $choice);
	}

	/**
     * An identification code for use by third-party applications to identify
     * transactions. Character length and limitations: 32 single-byte
     * alphanumeric characters
	 *
	 * @return string
	 */
	public function getButtonSource() {
        return $this->collection->getValue('BUTTONSOURCE');
	}

	/**
     * An identification code for use by third-party applications to identify
     * transactions. Character length and limitations: 32 single-byte
     * alphanumeric characters
	 *
     * @param string $source
	 */
	public function setButtonSource($source) {
        $this->collection->setValue('BUTTONSOURCE', $source);
	}

	/**
	 * @return array<Payment>
	 */
	public function getPayements() {
		return $this->payments;
	}

    /**
     *
     * @param array<Payment> $payments
     */
	public function setPayements(array $payments) {
		$this->payments = $payments;
	}

	/**
	 *
	 * @return UserOptions
	 */
	public function getUserOptions() {
		return $this->userOptions;
	}

	/**
	 *
	 * @param UserOptions
	 */
	public function setUserOptions(UserOptions $userOptions) {
		$this->userOptions = $userOptions;
	}

    public function getNVPRequest() {

		$request = $this->collection->getAllValues();

		/* payment */
		foreach($this->payments as $index => $payment) {
			foreach($payment->getNVPArray() as $key => $value) {
				if (is_array($value)) {	// payment item is array and has to start with L_
					foreach($value as $itemIndex => $item) {
						foreach($item as $k => $v) {
							$request['L_PAYMENTREQUEST_' . $index . '_' . $k . $itemIndex] = $v;
						}
					}
				} else {
					$request['PAYMENTREQUEST_' . $index . '_' . $key] = $value;
				}
			}
		}

        /* user selected options */
        if ($this->userOptions != null) {
            $request = array_merge($request, $this->userOptions->getNVPArray());
        }

		return $request;
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new DoExpressCheckoutPaymentResponse($nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}
