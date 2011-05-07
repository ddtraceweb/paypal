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
final class Shipping implements Field {

    /**
     * @var Collection
     */
	private $collection;

    private static $allowedValues = array('SHIPPINGOPTIONISDEFAULT',
        'SHIPPINGOPTIONNAME', 'SHIPPINGOPTIONAMOUNT');

	private function  __construct() { }

    public static function getRequest() {

        $shipping = new self();
        $shipping->collection = new Collection(self::$allowedValues, null);
        return $shipping;
    }

	/**
	 * Shipping option. Required if specifying the Callback URL. When the value
	 * of this flat rate shipping option is true, PayPal selects it by default
	 * for the buyer and reflects it in the "default" total.
	 * NOTE: There must be ONE and ONLY ONE default. It is not OK to have no
	 * default.
	 *
	 * @param boolean $default
	 */
	public function setDefaultOption($default) {

        $default = ($default) ? 'true' : 'false';
        $this->collection->setValue('SHIPPINGOPTIONISDEFAULT', $default);
	}

	/**
	 * Shipping option. Required if specifying the Callback URL. The internal
	 * name of the shipping option such as Air, Ground, Expedited, and so
	 * forth. Character length and limitations: 50 character-string.
	 *
	 * @param String $name
	 */
	public function setName($name) {
        $this->collection->setValue('SHIPPINGOPTIONNAME', $name);
	}

	/**
	 * Shipping option. Required if specifying the Callback URL. The amount of
	 * the flat rate shipping option. Limitations: Must not exceed $10,000 USD
	 * in any currency. No currency symbol. Must have two decimal places,
	 * decimal separator must be a period (.), and the optional thousands
	 * separator must be a comma (,).
	 *
	 * @param String $amount
	 */
	public function setAmount($amount) {
        $this->collection->setValue('SHIPPINGOPTIONAMOUNT', $amount);
	}

	public function getNVPArray() {
		return $this->collection->getAllValues();
	}

	private function  __clone() { }
}

