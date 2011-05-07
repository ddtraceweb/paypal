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
require_once __DIR__ . '/../Fields/PayerInformation.php';
require_once __DIR__ . '/../Fields/PayerName.php';
require_once __DIR__ . '/../Fields/Payment.php';
require_once __DIR__ . '/../Fields/UserOptions.php';

use PayPalNVP\Response\Response,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\PayerInformation,
    PayPalNVP\Fields\PayerName,
    PayPalNVP\Fields\Payment,
    PayPalNVP\Fields\UserOptions,
    PayPalNVP\Environment;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class GetExpressCheckoutDetailsResponse extends Response {

	/** @var PayerInformation */
	private $payerInformation;

	/** @var PayerName */
	private $payerName;

	/** @var UserOptions */
	private $userOptions;

	/** @var array<Payment> */
	private $payment = array();

    /**
     * @var Collection
     */
    private $collection;

    private static $allowedValues = array('TOKEN', 'CUSTOM', 'INVNUM',
        'PHONENUM', 'PAYPALADJUSTMENT', 'NOTE', 'REDIRECTREQUIRED',
        'CHECKOUTSTATUS', 'GIFTMESSAGE', 'GIFTRECEIPTENABLE', 'GIFTWRAPNAME',
        'GIFTWRAPAMOUNT', 'BUYERMARKETINGEMAIL', 'SURVEYQUESTION',
        'SURVEYCHOICESELECTED');

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);

        $responseArray = $this->getResponse();
        if (!empty($responseArray)) {
            $this->payerInformation = PayerInformation::getResponse($responseArray);
            $this->payerName = PayerName::getResponse($responseArray);
            $this->userOptions = UserOptions::getResponse($responseArray);
        }

        $responseArray = array();

		/* payment request */
		$payments = array();
		foreach($this->getResponse() as $key => $value) {
			$keyParts = explode('_', $key);
			if (!empty($keyParts)) {

				// PAYMENTREQUEST_n_VALUE
				if ($keyParts[0] == 'PAYMENTREQUEST') {

					$x = $keyParts[1];
					/* [index][key] = value  */
					$payments[$x][$keyParts[2]] = $value;

				// L_PAYMENTREQUEST_n_VALUEn
				} elseif ($keyParts[0] == 'L' && count($keyParts) > 3) {

					$x = $keyParts[2];
                    $rawValue = $keyParts[3];
                    preg_match('/[\d]+$/', $rawValue, $matches);
                    if (count($matches) == 1) {
                        $size = strlen($rawValue);
                        $index = $matches[0];
                        $indexSize = strlen($index);
                        $value = substr($rawValue, 0, $size - $indexSize);
                        $payments[$x][$index] = $value;
                    }
				}
			} else {
                $responseArray[$key] = $value;
            }
		}

        $this->collection = new Collection(self::$allowedValues, $responseArray);

		/* set payments */
		foreach ($payments as $index => $value) {
            $this->payment[$index] = Payment::getResponse($value);
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
	 * A free-form field for your own use, as set by you in the Custom element 
	 * of SetExpressCheckout request. Character length and limitations: 
	 * 256 single-byte alphanumeric characters
	 *
	 * @return string 
	 */
	public function getCustomField() {
        return $this->collection->getValue('CUSTOM');
	}

	/** 
	 * Your own invoice or tracking number, as set by you in the element of the 
	 * same name in SetExpressCheckout request. Character length and 
	 * limitations: 127 single-byte alphanumeric characters
	 *
	 * @return string 
	 */
	public function getInvoiceNumber() {
        return $this->collection->getValue('INVNUM');
	}

	/** 
	 * Payer's contact telephone number. NOTE:PayPal returns a contact 
	 * telephone number only if your Merchant account profile settings require 
	 * that the buyer enter one. Character length and limitations: Field mask 
	 * is XXX-XXX-XXXX (for US numbers) or +XXX XXXXXXXX 
	 * (for international numbers)
	 *
	 * @return string
	 */
	public function getPhoneNumber() {
        return $this->collection->getValue('PHONENUM');
	}

	/** 
	 * A discount or gift certificate offered by PayPal to the buyer. This 
	 * amount will be represented by a negative amount. If the buyer has a 
	 * negative PayPal account balance, PayPal adds the negative balance to the 
	 * transaction amount, which is represented as a positive value.
	 *
	 * @return string 
	 */
	public function getPayPalAdjustment() {
        return $this->collection->getValue('PAYPALADJUSTMENT');
	}

	/** 
	 * This field is deprecated. The text entered by the buyer on the PayPal 
	 * website if the ALLOWNOTE field was set to 1 in SetExpressCheckout. 
	 * Character length and limitations: 255 single-byte characters
	 *
	 * @return string 
	 */
	public function getNote() {
        return $this->collection->getValue('NOTE');
	}

	/** 
	 * Flag to indicate whether you need to redirect the customer to back to 
	 * PayPal after completing the transaction. NOTE: Use this field only if 
	 * you are using giropay or bank transfer payment methods in Germany.
	 *
	 * @return boolean 
	 */
	public function getRedirectRequired() {
        return $this->collection->getValue('REDIRECTREQUIRED');
	}

	/** 
	 * ebl: CheckoutStatusType. Returns the status of the checkout session. 
	 * Possible values are: PaymentActionNotInitiated, PaymentActionFailed, 
	 * PaymentActionInProgress, or PaymentCompleted 
	 * If payment is completed, the transaction identification number of the 
	 * resulting transaction is returned.
	 *
	 * @return string 
	 */
	public function getCheckoutStatus() {
        return $this->collection->getValue('CHECKOUTSTATUS');
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
	 * Returns true if the buyer requested a gift receipt on the PayPal Review
	 * page and false if the buyer did not.
	 *
	 * @return boolean
	 */
	public function getGiftReceipt() {
        return $this->collection->getValue('GIFTRECEIPTENABLE');
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
	 * Return the amount only if the gift option on the PayPal Review page is
	 * selected by the buyer.
	 *
	 * @return string
	 */
	public function getGiftWrapAmount() {
        return $this->collection->getValue('GIFTWRAPAMOUNT');
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
	 * The survey question on the PayPal Review page.
	 * Limitations: 50 single-byte characters
	 *
	 * @return string
	 */
	public function getSurveyQuestion() {
        return $this->collection->getValue('SURVEYQUESTION');
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
	 *
	 * @return PayerInformationResponse
	 */
	public function getPayerInformation() {
		return $this->payerInformation;
	}

	/**
	 *
	 * @return PayerNameResponse
	 */
	public function getPayerName() {
		return $this->payerName;
	}

	/**
	 * @return array<PaymentResponse>
	 */
	public function getPayements() {
		return $this->payment;
	}

	/**
	 *
	 * @return UserOptionsResponse
	 */
	public function getUserOptions() {
		return $this->userOptions;
	}

	private function  __clone() { }
}