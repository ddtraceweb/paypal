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
require_once __DIR__ . '/../Fields/UserOptions.php';
require_once __DIR__ . '/../Fields/PaymentInfo.php';
require_once __DIR__ . '/../Fields/PaymentError.php';

use PayPalNVP\Response\Response,
    PayPalNVP\Environment,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\UserOptions,
    PayPalNVP\Fields\PaymentInfo,
    PayPalNVP\Fields\PaymentError;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class DoExpressCheckoutPaymentResponse extends Response {

    // TODO - seller details - looks different than existing

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var UserOptions
     */
    private $userOptions;

    /**
     * @var array<PaymentInfo>
     */
    private $paymentInfo;

    /**
     * @var array<PaymentError>
     */
    private $paymentError;

    private static $allowedValues = array('TOKEN', 'PAYMENTTYPE', 'NOTE',
        'REDIRECTREQUIRED', 'SUCCESSPAGEREDIRECTREQUESTED');

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);

        $responseArray = array();
        $info = array();
        $error = array();
        foreach($this->getResponse() as $key => $value) {

			$keyParts = explode('_', $key);
			if (!empty($keyParts)) {

				if ($keyParts[0] == 'PAYMENTINFO') {

					$x = $keyParts[1];
					/* [index][key] = value  */
					$info[$x][$keyParts[2]] = $value;
                } elseif ($keyParts[0] == 'PAYMENTREQUEST') {

					$x = $keyParts[1];
					/* [index][key] = value  */
					$error[$x][$keyParts[2]] = $value;
                }
            } else {
                $responseArray[$key] = $value;
            }
        }

        $this->collection = new Collection(self::$allowedValues, $responseArray);

        foreach($info as $index => $value) {
            $this->paymentInfo[$index] = PaymentInfo::getResponse($value);
        }

        foreach($error as $index => $value) {
            $this->paymentError[$index] = PaymentError::getResponse($value);
        }

        $this->userOptions = UserOptions::getResponse($responseArray);
	}

    /** 
     * The timestamped token value that was returned by SetExpressCheckout 
     * response and passed on GetExpressCheckoutDetails request.
     *
     * @return string 
     */
    public function getToken() {
        return $this->collection->getValue('TOKEN');
    }

    /** 
     * Information about the payment.
     *
     * @return string 
     */
    public function getPaymentType() {
        return $this->collection->getValue('PAYMENTTYPE');
    }

    /**
     * The text entered by the buyer on the PayPal website if the ALLOWNOTE
     * field was set to 1 in SetExpressCheckout.
     *
     * @return string
     */
    public function getNote() {
        return $this->collection->getValue('NOTE');
    }

    /**
     * Flag to indicate whether you need to redirect the customer to back to
     * PayPal for guest checkout after successfully completing the transaction.
     * Note: Use this field only if you are using giropay or bank transfer
     * payment methods in Germany.
     *
     * @return string
     */
    public function getRedirectRequired() {
        return $this->collection->getValue('REDIRECTREQUIRED');
    }

    /**
     * Flag to indicate whether you need to redirect the customer to back to
     * PayPal after completing the transaction.
     *
     * @return string
     */
    public function getSuccessRedirectRequested() {
        return $this->collection->getValue('SUCCESSPAGEREDIRECTREQUESTED');
    }

    /**
     * @return array<PaymentInfo>
     */
    public function getPaymentInfo() {
        return $this->paymentInfo;
    }

    /**
     * @return array<PaymentError>
     */
    public function getPaymentError() {
        return $this->paymentError;
    }

    /**
     * @return UserOptions
     */
    public function getUserOptions() {
        return $this->userOptions;
    }

	private function  __clone() { }
}
