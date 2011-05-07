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
final class PaymentInfo implements Field {

    // TODO L_PAYMENTINFO_n_FMFfilterNAMEm, L_PAYMENTINFO_n_FMFfilterIDm

    /**
     * @var Collection
     */
	private $collection;

    private static $allowedValues = array('TRANSACTIONID', 'TRANSACTIONTYPE',
        'PAYMENTTYPE', 'ORDERTIME', 'AMT', 'CURRENCYCODE', 'FEEAMT',
        'SETTLEAMT', 'TAXAMT', 'EXCHANGERATE', 'PAYMENTSTATUS',
        'PENDINGREASON', 'REASONCODE', 'HOLDDECISION', 'PROTECTIONELIGIBILITY',
        'PROTECTIONELIGIBILITYTYPE', 'EBAYITEMAUCTIONTXNID', 'PAYMENTREQUESTID');

	private function  __construct() { }

    public static function getResponse(array $response) {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, $response);
        return $info;
    }

    /**
     * Unique transaction ID of the payment.
     * Note: If the PaymentAction of the request was Authorization or Order,
     * this value is your AuthorizationID for use with the Authorization &
     * Capture APIs.
     *
     * @return string
     */
    public function getTransactionId() {
        return $this->collection->getValue('TRANSACTIONID');
    }

    /**
     * The type of transaction
     * Valid values:
     * * cart
     * * express-checkout
     *
     * @return string
     */
    public function getTransactionType() {
        return $this->collection->getValue('TRANSACTIONTYPE');
    }

    /**
     * Indicates whether the payment is instant or delayed.
     * Valid values:
     * * none
     * * echeck
     * * instant
     *
     * @return string
     */
    public function getPaymentType() {
        return $this->collection->getValue('PAYMENTTYPE');
    }

    /**
     * Time/date stamp of payment
     *
     * @return string
     */
    public function getOrderTime() {
        return $this->collection->getValue('ORDERTIME');
    }

    /**
     * The final amount charged, including any shipping and taxes from your
     * Merchant Profile.
     *
     * @return string
     */
    public function getAmount() {
        return $this->collection->getValue('AMT');
    }

    /**
     * @return Currency
     */
    public function getCurrency() {

        $currency = $this->collection->getValue('CURRENCYCODE');
        return ($currency == null) ? $currency : new Currency($currency);
    }

    /**
     * PayPal fee amount charged for the transaction
     *
     * @return string
     */
    public function getFeeAmount() {
        return $this->collection->getValue('FEEAMT');
    }

    /**
     * Amount deposited in your PayPal account after a currency conversion.
     *
     * @return string
     */
    public function getSettleAmount() {
        return $this->collection->getValue('SETTLEAMT');
    }

    /**
     * Tax charged on the transaction.
     *
     * @return string
     */
    public function getTaxAmount() {
        return $this->collection->getValue('TAXAMT');
    }

    /**
     * Exchange rate if a currency conversion occurred. Relevant only if your
     * are billing in their non-primary currency. If the customer chooses to
     * pay with a currency other than the non-primary currency, the conversion
     * occurs in the customer’s account.
     *
     * @return string
     */
    public function getExchangeRate() {
        return $this->collection->getValue('EXCHANGERATE');
    }

    /**
     * The status of the payment:
     * * None: No status
     * * Canceled-Reversal: A reversal has been canceled; for example, when you
     *      win a dispute and the funds for the reversal have been returned to
     *      you.
     * * Completed: The payment has been completed, and the funds have been
     *      added successfully to your account balance.
     * * Denied: You denied the payment. This happens only if the payment was
     *      previously pending because of possible reasons described for the
     *      PendingReason element.
     * * Expired: the authorization period for this payment has been reached.
     * * Failed: The payment has failed. This happens only if the payment was
     *      made from your customer’s bank account.
     * * In-Progress: The transaction has not terminated, e.g. an authorization
     *      may be awaiting completion.
     * * Partially-Refunded: The payment has been partially refunded.
     * * Pending: The payment is pending. See the PendingReason field for more
     *      information.
     * * Refunded: You refunded the payment.
     * * Reversed: A payment was reversed due to a chargeback or other type of
     *      reversal. The funds have been removed from your account balance and
     *      returned to the buyer. The reason for the reversal is specified in
     *      the ReasonCode element.
     * * Processed: A payment has been accepted.
     * * Voided: An authorization for this transaction has been voided.
     * * Completed-Funds-Held: The payment has been completed, and the funds
     *      have been added successfully to your pending balance.
     * See the HOLDDECISION field for more information.
     *
     * @return string
     */
    public function getPaymentStatus() {
        return $this->collection->getValue('PAYMENTSTATUS');
    }

    /**
     * Note: PendingReason is returned in the response only if PaymentStatus is
     * Pending.
     * The reason the payment is pending:
     * * none: No pending reason.
     * * address: The payment is pending because your customer did not include
     *      a confirmed shipping address and your Payment Receiving Preferences
     *      is set such that you want to manually accept or deny each of these
     *      payments. To change your preference, go to the Preferences section
     *      of your Profile.
     * * authorization: The payment is pending because it has been authorized
     *      but not settled. You must capture the funds first.
     * * echeck: The payment is pending because it was made by an eCheck that
     *      has not yet cleared.
     * * intl: The payment is pending because you hold a non-U.S. account and
     *      do not have a withdrawal mechanism. You must manually accept or
     *      deny this payment from your Account Overview.
     * * multi-currency: You do not have a balance in the currency sent, and
     *      you do not have your Payment Receiving Preferences set to
     *      automatically convert and accept this payment. You must manually
     *      accept or deny this payment.
     * * order: The payment is pending because it is part of an order that has
     *      been authorized but not settled.
     * * paymentreview: The payment is pending while it is being reviewed by
     *      PayPal for risk.
     * * unilateral: The payment is pending because it was made to an email
     *      address that is not yet registered or confirmed.
     * * verify: The payment is pending because you are not yet verified. You
     *      must verify your account before you can accept this payment.
     * * other: The payment is pending for a reason other than those listed
     *      above. For more information, contact PayPal customer service.
     *
     * @return string
     */
    public function getPendingReason() {
        return $this->collection->getValue('PENDINGREASON');
    }

    /**
     * The reason for a reversal if TransactionType is reversal:
     * * none: No reason code
     * * chargeback: A reversal has occurred on this transaction due to a
     *      chargeback by your customer.
     * * guarantee: A reversal has occurred on this transaction due to your
     *      customer triggering a money-back guarantee.
     * * buyer-complaint: A reversal has occurred on this transaction due to a
     *      complaint about the transaction from your customer.
     * * refund: A reversal has occurred on this transaction because you have
     *      given the customer a refund.
     * * other: A reversal has occurred on this transaction due to a reason not
     *      listed above.
     *
     * @return string
     */
    public function getReasonCode() {
        return $this->collection->getValue('REASONCODE');
    }

    /**
     * This field is returned only if PAYMENTSTATUS is Completed-Funds-Held..
     * Is one of the following values:
     * * newsellerpaymenthold - This is a new seller.
     * * paymenthold - A hold is placed on the seller’s transaction for a
     *      reason not listed.
     *
     * @return string
     */
    public function getHoldDecision() {
        return $this->collection->getValue('HOLDDECISION');
    }

    /**
     * The kind of seller protection in force for the transaction, which is one
     * of the following values:
     * * Eligible – Seller is protected by PayPal's Seller Protection Policy
     *      for both Unauthorized Payment and Item Not Received
     * * ItemNotReceivedEligible – Seller is protected by PayPal's Seller
     *      Protection Policy for Item Not Received
     * * UnauthorizedPaymentEligible – Seller is protected by PayPal's Seller
     *      Protection Policy for Unauthorized Payment
     * * Ineligible – Seller is not protected under the Seller Protection Policy
     *
     * @return string
     */
    public function getProtectionEligibilityType() {
        return $this->collection->getValue('PROTECTIONELIGIBILITYTYPE');
    }

    /**
     * The eBay transaction identification number.
     *
     * @return string
     */
    public function getEbayTransactionId() {
        return $this->collection->getValue('EBAYITEMAUCTIONTXNID');
    }

    /**
     * The unique identifier of the specific payment request. The value should
     * match the one passed in the DoExpressCheckout request.
     *
     * @return string
     */
    public function getPaymentRequestId() {
        return $this->collection->getValue('PAYMENTREQUESTID');
    }

	public function getNVPArray() {
		return $this->collection->getAllValues();
	}

	private function  __clone() { }
}

