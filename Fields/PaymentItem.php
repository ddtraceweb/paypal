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
require_once 'Item.php';
require_once __DIR__ . '/../Util/ItemCategory.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Item,
    PayPalNVP\Fields\Field,
    PayPalNVP\Util\ItemCategory;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class PaymentItem implements Item, Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('NAME', 'DESC', 'AMT', 'NUMBER',
        'QTY', 'TAXAMT', 'ITEMWEIGHTVALUE', 'ITEMWEIGHTUNIT', 'ITEMLENGTHVALUE',
        'ITEMLENGTHUNIT', 'ITEMWIDTHVALUE', 'ITEMWIDTHUNIT', 'ITEMHEIGHTVALUE',
        'ITEMHEIGHTUNIT', 'ITEMCATEGORY');

    private function __construct() { }

    /**
	 * Cost of item. Character length and limitations: Must not exceed $10,000
	 * USD in any currency. No currency symbol. Regardless of currency, decimal
	 * separator must be a period (.), and the optional thousands separator
	 * must be a comma (,). Equivalent to nine characters maximum for USD.
     *
	 * @param String $amount
     * @return PaymentItem to be used as request
     */
    public static function getRequest($amount) {

        $paymentItem = new self();
        $paymentItem->collection = new Collection(self::$allowedValues, null);
		$paymentItem->collection->setValue('AMT', $amount);
        return $paymentItem;
    }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return PaymentItem as response
     */
    public static function getResponse(array $response) {

        $paymentItem = new self();
        $paymentItem->collection = new Collection(self::$allowedValues, $response);
        return $paymentItem;
    }

    /**
     * @return string cost of item
     */
	public function getAmount() {
		return $this->collection->getValue('AMT');
	}

	/**
	 * @return String Item name
	 */
	public function getName() {
		return $this->collection->getValue('NAME');
	}

	/**
	 * Item name. Character length and limitations: 127 single-byte characters
	 *
	 * @param String $name
	 */
	public function setName($name) {
		$this->collection->setValue('NAME', $name);
	}

	/**
	 * @return String Item description.
	 */
	public function getDescription() {
		return $this->collection->getValue('DESC');
	}

	/**
	 * Item description. Character length and limitations: 127 single-byte
	 * characters
	 *
	 * @param String $description
	 */
	public function setDescription($description) {
		$this->collection->setValue('DESC', $description);
	}

    /**
     * @return string Item number
     */
	public function getNumber() {
		return $this->collection->getValue('NUMBER');
	}

	/**
	 * Item number
	 *
	 * @param String $number
	 */
	public function setNumber($number) {
		$this->collection->setValue('NUMBER', $number);
	}

    /**
     * @return string Item quantity
     */
	public function getQuantity() {
		return $this->collection->getValue('QTY');
	}

	/**
	 * Item quantity. Character length and limitations: Any positive integer
	 *
	 * @param Integer $quantity
	 */
	public function setQuantity($quantity) {
		$this->collection->setValue('QTY', $quantity);
	}

    /**
     * @return string Item sales tax
     */
	public function getTaxAmount() {
		return $this->collection->getValue('TAXAMT');
	}

	/**
	 * Item sales tax. Character length and limitations: Must not exceed
	 * $10,000 USD in any currency. No currency symbol. Regardless of currency,
	 * decimal separator must be a period (.), and the optional thousands
	 * separator must be a comma (,). Equivalent to nine characters maximum for
	 * USD.
	 *
	 * @param String $amount
	 */
	public function setTaxAmount($amount) {
		$this->collection->setValue('TAXAMT', $amount);
	}

    /**
     * Item weight corresponds to the weight of the item.
     * @return array key is unit and value is weight
     */
	public function getWeight() {

        $value = array();
        $value[$this->collection->getValue('ITEMWEIGHTUNIT')] = $this->collection->getValue('ITEMWEIGHTUNIT');
        return $value;
	}

	/**
	 * Item weight corresponds to the weight of the item. You can pass this
	 * data to the shipping carrier as is without having to make an additional
	 * database query.
	 *
	 * @param String $unit
	 * @param String $weight
	 */
	public function setWeight($unit, $weight) {

		$this->collection->setValue('ITEMWEIGHTUNIT', $unit);
		$this->collection->setValue('ITEMWEIGHTVALUE', $weight);
	}

    /**
     * Item length corresponds to the length of the item.
     * @return array key is unit and value is length
     */
	public function getLength() {

        $value = array();
        $value[$this->collection->getValue('ITEMLENGTHUNIT')] = $this->collection->getValue('ITEMLENGTHVALUE');
        return $value;
	}

	/**
	 * Item length corresponds to the length of the item. You can pass this
	 * data to the shipping carrier as is without having to make an additional
	 * database query.
	 *
	 * @param String $unit
	 * @param Integer $length	positive integer
	 */
	public function setLength($unit, $length) {

		$this->collection->setValue('ITEMLENGTHUNIT', $unit);
		$this->collection->setValue('ITEMLENGTHVALUE', $length);
	}

    /**
     * Item width corresponds to the width of the item.
     * @return array key is unit and value is width
     */
	public function getWidth() {

        $value = array();
        $value[$this->collection->getValue('ITEMWIDTHUNIT')] = $this->collection->getValue('ITEMWIDTHVALUE');
        return $value;
	}

	/**
	 * Item width corresponds to the width of the item. You can pass this data
	 * to the shipping carrier as is without having to make an additional
	 * database query.
	 *
	 * @param String $unit
	 * @param Integer $width	positive integer
	 */
	public function setWidth($unit, $width) {

		$this->collection->setValue('ITEMWIDTHUNIT', $unit);
		$this->collection->setValue('ITEMWIDTHVALUE', $width);
	}

    /**
     * Item height corresponds to the height of the item.
     * @return array key is unit and value is height
     */
	public function getHeight() {

        $value = array();
        $value[$this->collection->getValue('ITEMHEIGHTUNIT')] = $this->collection->getValue('ITEMHEIGHTVALUE');
        return $value;
	}

	/**
	 * Item height corresponds to the height of the item. You can pass this
	 * data to the shipping carrier as is without having to make an additional
	 * database query.
	 *
	 * @param String $unit
	 * @param Integer $height	positive integer
	 */
	public function setHeight($unit, $height) {

		$this->collection->setValue('ITEMHEIGHTUNIT', $unit);
		$this->collection->setValue('ITEMHEIGHTVALUE', $height);
	}

	/**
	 *
	 * @param String $url url for the item
	 */
	public function setItemUrl($url) {
		$this->collection->setValue('ITEMURL', $url);
	}

    /**
     * @param ItemCategory $itemCategory
     */
    public function getItemCategory() {

        $category = $this->collection->getValue($key);
        if ($category == 'digital') {
            return ItemCategory::getDigital();
        }
        if ($category == 'physical') {
            return ItemCategory::getPhysical();
        }
        return null;
    }

    /**
     * @param ItemCategory $itemCategory
     */
    public function setItemCategory(ItemCategory $itemCategory) {
		$this->collection->setValue('ITEMCATEGORY', $itemCategory);
    }

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}
