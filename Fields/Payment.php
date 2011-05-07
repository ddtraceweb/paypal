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
require_once 'ShippingAddress.php';
require_once 'Seller.php';
require_once 'Shipping.php';
require_once 'PaymentError.php';
require_once __DIR__ . '/../Util/Currency.php';
require_once __DIR__ . '/../Util/PaymentAction.php';

use PayPalNVP\Fields\RequestFields,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\ShippingAddress,
    PayPalNVP\Fields\Seller,
    PayPalNVP\Fields\Shipping,
    PayPalNVP\Fields\PaymentError,
    PayPalNVP\Util\Currency,
    PayPalNVP\Util\PaymentAction;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class Payment implements Field {

    /**
     * @var boolean true if this is request, false if response
     */
    private $request;

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('AMT', 'CURRENCYCODE', 'ITEMAMT',
        'SHIPPINGAMT', 'INSURANCEAMT', 'SHIPDISCAMT', 'INSURANCEOPTIONOFFERED',
        'HANDLINGAMT', 'TAXAMT', 'DESC', 'CUSTOM', 'INVNUM', 'NOTIFYURL',
        'NOTETEXT', 'TRANSACTIONID', 'ALLOWEDPAYMENTMETHOD', 'PAYMENTREQUESTID');

    /** @var ShippingAddress */
    private $address = null;

    /** @var Seller */
    private $seller = null;

	/**  @var array<Item> */
	private $items = array();

	/**  @var array<PaymentError> */
	private $error = array();

	private function  __construct(array $response = null) { }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'PAYMENTREQUEST_n_', if key starts with
     * 'L_PAYMENTREQUEST_n_...m' it needs to be converted into an array.
     *
     * @return Payment as response
     */
    public static function getResponse(array $response) {

        $payment = new self($response);
        $payment->request = false;

        $paymentResponse = array();
        foreach($response as $key => $value) {
            /* array is on of the items */
            if (is_array($value)) {

                $item = PaymentItem::getResponse($value);
                $nvpArray = $item->getNVPArray();
                if (!empty($nvpArray)) {
                    $items[] = $item;
                    continue;
                }

                $item = EbayItem::getResponse($value);
                $nvpArray = $item->getNVPArray();
                if (!empty($nvpArray)) {
                    $items[] = $item;
                    continue;
                }
            } else {
                $paymentResponse[$key] = $value;
            }
        }
        /* set payment fields */
        $payment->collection = new Collection(self::$allowedValues, $paymentResponse);

        /* address */
        $address = ShippingAddress::getResponse($paymentResponse);
        $nvpArray = $address->getNVPArray();
        if (!empty($nvpArray)) {
            $payment->address = $address;
        }

        /* seller */
        $seller = Seller::getResponse($paymentResponse);
        $nvpArray = $seller->getNVPArray();
        if (!empty($nvpArray)) {
            $payment->seller = $seller;
        }

        /* error */
        $error = PaymentError::getResponse($paymentResponse);
        $nvpArray = $error->getNVPArray();
        if (!empty($nvpArray)) {
            $payment->error = $error;
        }

        return $payment;
    }

    /**
     *
     * @param array<Item>
     * @return Payment as request
     */
    public static function getRequest(array $items) {

        $payment = new self();
        $payment->request = true;
        $payment->collection = new Collection(self::$allowedValues, null);
        $payment->items = $items;
		return $payment;
    }

    /**
	 * Cost of item. Character length and limitations: Must not exceed $10,000
	 * USD in any currency. No currency symbol. Regardless of currency, decimal
	 * separator must be a period (.), and the optional thousands separator
	 * must be a comma (,). Equivalent to nine characters maximum for USD.
     *
	 * @param String $amount
     * @return Payment to be used as request
     */
    public static function getSimpleRequest($amount) {

        $payment = new self();
		$payment->collection->setValue('AMT', $amount);
        return $payment;
    }

	/**
	 * If not specified defaults to USD
	 * @param Currency $currency
	 */
	public function setCurrency(Currency $currency) {
		$this->collection->setValue('CURRENCYCODE', $currency->getCode());
	}

    /**
     * @return Currency
     */
	public function getCurrency() {
        return new Currency($this->collection->getValue('CURRENCYCODE'));
	}

	/**
	 * Shipping address
	 *
	 * @param ShippingAddress $address
	 */
	public function setShippingAddress(ShippingAddress $address) {
        $this->address = $address;
	}

    /**
     * @return ShippingAddress
     */
	public function getShippingShippingAddress() {
        return $this->address;
	}

	/**
	 *
	 * @param Seller $seller
	 */
	public function setSeller(Seller $seller) {
        $this->seller = $seller;
	}

    /**
     * @return Seller
     */
	public function getSeller() {
        return $this->seller;
	}

	/**
	 * Total shipping costs for this order.
	 * Shipping amount can be specified only if payment contains at least one
	 * PaymentItem with amount set.
	 * Character length and limitations: Must not exceed $10,000 USD in any
	 * currency. No currency symbol. Regardless of currency, decimal separator
	 * must be a period (.), and the optional thousands separator must be a
	 * comma (,). Equivalent to nine characters maximum for USD.
	 *
	 * @param String $amount
	 */
	public function setShippingAmount($amount) {
		$this->collection->setValue('SHIPPINGAMT', $amount);
	}

    /**
	 * Total shipping costs for this order.
     * @return String
     */
	public function getShippingAmount() {
		return $this->collection->getValue('SHIPPINGAMT');
	}

	/**
	 * Total shipping insurance costs for this order. The value must be a
	 * non-negative currency amount or null if insurance options are offered.
	 * Character length and limitations: Must not exceed $10,000 USD in any
	 * currency. No currency symbol. Regardless of currency, decimal separator
	 * must be a period (.), and the optional thousands separator must be a
	 * comma (,). Equivalent to nine characters maximum for USD.
	 *
	 * @param String $amount
	 * @param boolean $option	If true, the Insurance drop-down on the PayPal
	 *							Review page displays the string 'Yes' and the
	 *							insurance amount.
	 */
	public function setInsuranceAmount($amount, $option = null) {

		$this->collection->setValue('INSURANCEAMT', $amount);
		if ($option != null) {
            $option = ($option) ? 'true' : 'false';
            $this->collection->setValue('INSURANCEOPTIONOFFERED', $option);
		}
	}

    /**
	 * Total shipping insurance costs for this order.
     *
     * @return String
     */
	public function getInsuranceAmount() {
        return $this->collection->getValue('INSURANCEAMT');
    }

    /**
     *
     * @return String
     */
	public function getInsuranceOptionOffered() {
        return $this->collection->getValue('INSURANCEOPTIONOFFERED');
    }

	/**
	 * Shipping discount for this order, specified as a negative number.
	 * Character length and limitations: Must not exceed $10,000 USD in any
	 * currency. No currency symbol. Regardless of currency, decimal separator
	 * must be a period (.), and the optional thousands separator must be a
	 * comma (,). Equivalent to nine characters maximum for USD.
	 *
	 * @param String $amount
	 */
	public function setShippingDiscount($amount) {

        /* discount has to be negative number */
        if ($amount > 0) {
            $amount *= -1;
        }
        $this->collection->setValue('SHIPDISCAMT', $amount);
	}

    /**
	 * Shipping discount for this order.
     *
     * @return String
     */
	public function getShippingDiscount() {
        return $this->collection->getValue('SHIPDISCAMT');
    }

	/**
	 * Total handling costs for this order.
	 * Handling amount can be specified only if payment contains at least one
	 * PaymentItem with amount set.
	 * Character length and limitations: Must not exceed $10,000 USD in any
	 * currency. No currency symbol. Regardless of currency, decimal separator
	 * must be a period (.), and the optional thousands separator must be a
	 * comma (,). Equivalent to nine characters maximum for USD.
	 *
	 * @param String $amount
	 */
	public function setHandlingAmount($amount) {
        $this->collection->setValue('HANDLINGAMT', $amount);
	}

    /**
	 * Total handling costs for this order.
     *
     * @return String
     */
	public function getHandlingAmount() {
        return $this->collection->getValue('HANDLINGAMT');
    }

	/**
	 * Description of items the customer is purchasing.
	 * The value you specify is only available if the transaction includes a
	 * purchase; this field is ignored if you set up a billing agreement for a
	 * recurring payment that is not immediately charged.
	 * Character length and limitations: 127 single-byte alphanumeric characters
	 *
	 * @param String $description
	 */
	public function setDescription($description) {
        $this->collection->setValue('DESC', $description);
	}

    /**
	 * Description of items the customer is purchasing.
     *
     * @return String
     */
	public function getDescription() {
        return $this->collection->getValue('DESC');
    }

	/**
	 * A free-form field for your own use.
	 * NOTE:The value you specify is only available if the transaction includes
	 * a purchase; this field is ignored if you set up a billing agreement for
	 * a recurring payment that is not immediately charged.
	 * Character length and limitations: 256 single-byte alphanumeric characters
	 *
	 * @param String $custom
	 */
	public function setCustomField($custom) {
        $this->collection->setValue('CUSTOM', $custom);
	}

    /**
	 * A free-form field for your own use.
     *
     * @return String
     */
	public function getCustomField() {
        return $this->collection->getValue('CUSTOM');
	}

	/**
	 * Your own invoice or tracking number.
	 * NOTE:The value you specify is only available if the transaction includes
	 * a purchase; this field is ignored if you set up a billing agreement for
	 * a recurring payment that is not immediately charged.
	 * Character length and limitations: 127 single-byte alphanumeric characters
	 *
	 * @param String $number
	 */
	public function setInvoiceNumber($number) {
        $this->collection->setValue('INVNUM', $number);
	}

    /**
	 * Your own invoice or tracking number.
     *
     * @return String
     */
	public function getInvoiceNumber() {
        return $this->collection->getValue('INVNUM');
    }

	/**
	 * Your URL for receiving Instant Payment Notification (IPN) about this
	 * transaction. If you do not specify this value in the request,
	 * the notification URL from your Merchant Profile is used, if one exists.
	 * IMPORTANT: The notify URL only applies to DoExpressCheckoutPayment.
	 * This value is ignored when set in SetExpressCheckout or
	 * GetExpressCheckoutDetails.
	 * Character length and limitations: 2,048 single-byte alphanumeric
	 * characters
	 *
	 * @param String $url
	 */
	public function setNotifyUrl($url) {
        $this->collection->setValue('NOTIFYURL', $url);
	}

    /**
	 * Your URL for receiving Instant Payment Notification (IPN) about this
	 * transaction.
     *
     * @return String
     */
	public function getNotifyUrl() {
        return $this->collection->getValue('NOTIFYURL');
	}

	/**
	 * Note to the merchant.
	 * Character length and limitations: 255 single-byte characters
	 *
	 * @param String $note
	 */
	public function setNote($note) {
        $this->collection->setValue('NOTETEXT', $note);
	}

    /**
	 * Note to the merchant.
     *
     * @return String
     */
	public function getNote() {
        return $this->collection->getValue('NOTETEXT');
	}

	/**
	 * Transaction identification number of the transaction that was created.
	 * NOTE: This field is only returned after a successful transaction for
	 * DoExpressCheckout has occurred.
	 *
	 * @param String $id
	 */
	public function setTransactionId($id) {
        $this->collection->setValue('TRANSACTIONID', $id);
	}

    /**
	 * Transaction identification number of the transaction that was created.
	 * NOTE: This field is only returned after a successful transaction for
	 * DoExpressCheckout has occurred.
     *
     * @return String
     */
	public function getTransactionId() {
        return $this->collection->getValue('TRANSACTIONID');
	}

    /**
     * The payment method type. Specify the value InstantPaymentOnly.
     * @param string $method
     */
	public function setAllowedPaymentMethod($method) {
        $this->collection->setValue('ALLOWEDPAYMENTMETHOD', $method);
	}

    /**
     * The payment method type.
     *
     * @return String
     */
	public function getAllowedPaymentMethod() {
        return $this->collection->getValue('ALLOWEDPAYMENTMETHOD');
	}

	/**
     * If this value is set, then it needs to be set in DoExpressCheckout as
     * well, because this value is not returned in express checkout details !!!
     *
     * When implementing parallel payments, this field is set to Order
     * automatically and cannot be changed.
     *
	 * How you want to obtain payment.
	 * <b>Sale</b> indicates that this is a final sale for which you are
	 * requesting payment. (Default)
     *
	 * <b>Authorization</b> indicates that this payment is a basic
	 * authorization subject to settlement with PayPal Authorization & Capture.
     *
	 * <b>Order</b> indicates that this payment is an order authorization
	 * subject to settlement with PayPal Authorization & Capture.
     *
     * If the transaction does not include a one-time purchase, this field is
     * ignored.
     *
	 * NOTE: You cannot set this value to Sale in SetExpressCheckout request
	 * and then change this value to Authorization or Order on the final API
	 * DoExpressCheckoutPayment request. If the value is set to Authorization
	 * or Order in SetExpressCheckout, the value may be set to Sale or the same
	 * value (either Authorization or Order) in DoExpressCheckoutPayment.
	 * Default value: Sale
	 * Character length and limit: Up to 13 single-byte alphabetic characters
	 *
	 * @param PaymentAction $action
	 */
	public function setPaymentAction(PaymentAction $action) {
        $this->collection->setValue('PAYMENTACTION', $action->getValue());
	}

    /**
     * If this value is set, then it needs to be set in DoExpressCheckout as
     * well, because this value is not returned in express checkout details !!!
     *
     * When implementing parallel payments, this field is set to Order
     * automatically and cannot be changed.
     *
	 * How you want to obtain payment.
	 * <b>Sale</b> indicates that this is a final sale for which you are
	 * requesting payment. (Default)
     *
	 * <b>Authorization</b> indicates that this payment is a basic
	 * authorization subject to settlement with PayPal Authorization & Capture.
     *
	 * <b>Order</b> indicates that this payment is an order authorization
	 * subject to settlement with PayPal Authorization & Capture.
     *
     * If the transaction does not include a one-time purchase, this field is
     * ignored.
     *
	 * NOTE: You cannot set this value to Sale in SetExpressCheckout request
	 * and then change this value to Authorization or Order on the final API
	 * DoExpressCheckoutPayment request. If the value is set to Authorization
	 * or Order in SetExpressCheckout, the value may be set to Sale or the same
	 * value (either Authorization or Order) in DoExpressCheckoutPayment.
	 * Default value: Sale
	 * Character length and limit: Up to 13 single-byte alphabetic characters
     *
     * @return PaymentAction
     */
	public function getPaymentAction() {
        return $this->collection->getValue('PAYMENTACTION');
	}

	/**
	 * A unique identifier of the specific payment request.
	 * Required for paralel payments.
	 * Character length and limit: Up to 127 single-byte characters
	 *
	 * @param String $id
	 */
	public function setPaymentRequestId($id) {
        $this->collection->setValue('PAYMENTREQUESTID', $id);
	}

    /**
	 * A unique identifier of the specific payment request.
     *
     * @return String
     */
	public function getPaymentRequestId() {
        return $this->collection->getValue('PAYMENTREQUESTID');
	}

    /**
     * Calling object needs to prepend 'PAYMENTREQUEST_n_', if value is an
     * array then needs to prepend 'L_PAYMENTREQUEST_n_...m'
     *
     * @return array
     */
	public function getNVPArray() {

        /* response */
        if (!$this->request) {

            $response = $this->collection->getAllValues();
            if ($this->address != null) {
                $response = array_merge($response, $this->address->getNVPArray());
            }
            if ($this->seller != null) {
                $response = array_merge($response, $this->seller->getNVPArray());
            }

            foreach($this->items as $index => $value) {
                $response[$index] = $value->getNVPArray();
            }

            return $response;
        }

        /* request */

		$response = array();
		/* total amount of all items */
		$itemAmount = 0;
		/* total tax of all items */
		$taxAmount = 0;
		/* items in this payment */
		$items = array();

		/* add amount and tax from all items and create keys for nvp */
		foreach($this->items as $index => $item) {
			foreach($item->getNVPArray() as $key => $value) {
				if ($key == 'AMT') {
					$itemAmount += $value;
				}
				if ($key == 'TAXAMT') {
					$taxAmount += $value;
				}
				$items[$index][$key] = $value;
			}
		}

		if ($itemAmount > 0) {
			$response['ITEMAMT'] = $itemAmount;
		}
		if ($taxAmount > 0) {
			$response['TAXAMT'] = $taxAmount;
		}
		/* add everything to the final amount - AMT */
		$response['AMT'] = $itemAmount + $taxAmount;
		if (isset($this->nvpRequest['SHIPPINGAMT'])) {
			$respone['AMT'] += $this->nvpRequest['SHIPPINGAMT'];
			if (isset($this->nvpRequest['SHIPDISCAMT'])) {
				$respone['AMT'] -= $this->nvpRequest['SHIPDISCAMT'];
			}
		}
		if (isset($this->nvpRequest['INSURANCEAMT'])) {
			$response['AMT'] += $this->nvpRequest['INSURANCEAMT'];
		}
		if (isset($this->nvpRequest['HANDLINGAMT'])) {
			$response['AMT'] += $this->nvpRequest['HANDLINGAMT'];
		}

		/* add items as an array */
		$response['items'] = $items;

		/* merge nvp string with the values autogenarated from items */
		return array_merge($this->collection->getAllValues(), $response);
	}

	private function  __clone() { }
}