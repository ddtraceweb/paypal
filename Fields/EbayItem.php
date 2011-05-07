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

require_once 'Collection.php';
require_once 'Item.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Item;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class EbayItem implements Item, Field {

    /**
     * @var Collection
     */
    private $collection;

    private static $allowedValues = array('EBAYITEMNUMBER', 'EBAYITEMAUCTIONTXNID',
        'EBAYITEMORDERID', 'EBAYCARTID');

    /**
     * @return EbayItem to be used as request
     */
    public static function getRequest() {

        $ebayItem = new self();
        $ebayItem->collection = new Collection(self::$allowedValues, null);
        return $ebayItem;
    }

    private function __construct() { }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return EbayItem as response
     */
    public static function getResponse(array $response) {

        $ebayItem = new self();
        $ebayItem->collection = new Collection(self::$allowedValues, $response);
        return $ebayItem;
    }

	/**
     * @return string Auction item number.
	 */
	public function getItemNumber() {
		$this->collection->getValue('EBAYITEMNUMBER');
	}

	/**
     * Auction item number. Character length: 765 single-byte characters
	 * @param String $number
	 */
	public function setItemNumber($number) {
        $this->collection->setValue('EBAYITEMNUMBER', $number);
	}

	/**
     * @return string Auction transaction identification number.
	 */
	public function getTransactionNumber() {
		$this->collection->getValue('EBAYITEMAUCTIONTXNID');
	}

	/**
     * Auction transaction identification number. Character length: 255
     * single-byte characters
	 * @param String $number
	 */
	public function setTransactionNumber($number) {
        $this->collection->setValue('EBAYITEMAUCTIONTXNID', $number);
	}

	/**
     * @return string Auction order identification number.
	 */
	public function getOrderId() {
		$this->collection->getValue('EBAYITEMORDERID');
	}

	/**
     * Auction order identification number.
	 * @param String $orderId
	 */
	public function setOrderId($orderId) {
        $this->collection->setValue('EBAYITEMORDERID', $orderId);
	}

	/**
     * @return string   The unique identifier provided by eBay for this order
     *                  from the buyer.
	 */
	public function getCartId() {
		$this->collection->getValue('EBAYCARTID');
	}

	/**
     * The unique identifier provided by eBay for this order from the buyer.
     * Character length: 255 single-byte characters
	 * @param String $cartId
	 */
	public function setCartId($cartId) {
        $this->collection->setValue('EBAYCARTID', $cartId);
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}