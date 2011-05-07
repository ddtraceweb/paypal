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
final class DoDirectPaymentResponse extends Response {

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

    private static $allowedValues = array('TRANSACTIONID', 'AMT', 'AVSCODE',
        'CVV2MATCH');

    // TODO - 3d response fields

            /*
TODO

L_FMFfilter ID n
Filter ID, including the filter type (PENDING, REPORT, or DENY), the filter ID, 
and the entry number, n, starting from 0. Filter ID is one of the following values:
    1 = AVS No Match
    2 = AVS Partial Match
    3 = AVS Unavailable/Unsupported
    4 = Card Security Code (CSC) Mismatch
    5 = Maximum Transaction Amount
    6 = Unconfirmed Address
    7 = Country Monitor
    8 = Large Order Number
    9 = Billing/Shipping Address Mismatch
    10 = Risky ZIP Code
    11 = Suspected Freight Forwarder Check
    12 = Total Purchase Price Minimum
    13 = IP Address Velocity
    14 = Risky Email Address Domain Check
    15 = Risky Bank Identification Number (BIN) Check
    16 = Risky IP Address Range
    17 = PayPal Fraud Model

L_FMFfilterNAMEn
Filter name, including the filter type, (PENDING, REPORT, or DENY), the filter 
NAME, and the entry number, n, starting from 0.
            */

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);
        $this->collection = new Collection(self::$allowedValues, $responseArray);
	}

    /* 
     * Unique transaction ID of the payment. Note: If the PaymentAction of the 
     * request was Authorization, the value of TransactionID is your 
     * AuthorizationID for use with the Authorization & Capture APIs. 
     *
     * @return string 
     */
    public function getTransactionId() {
        return $this->collection->getValue('TRANSACTIONID');
    }

    /** 
     * This value is the amount of the payment as specified by you on 
     * DoDirectPaymentRequest for reference transactions with direct payments.
     *
     * @return string 
     */
    public function getAmount() {
        return $this->collection->getValue('AMT');
    }

    /** 
     * Address Verification System response code. See AVS Response Codes for 
     * possible values.
     *
     * @return string
     */
    public function getAvsCode() {
        return $this->collection->getValue('AVSCODE');
    }

    /** 
     * Result of the CVV2 check by PayPal.
     *
     * @return string
     */
    public function getCvv2Code() {
        return $this->collection->getValue('CVV2MATCH');
    }

	private function  __clone() { }
}
