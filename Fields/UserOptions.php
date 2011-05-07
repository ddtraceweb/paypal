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
require_once 'Field.php';

use PayPalNVP\Fields\Collection,
    PayPalNVP\Fields\Field;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class UserOptions implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array(
		'SHIPPINGCALCULATIONMODE', 'INSURANCEOPTIONSELECTED',
		'SHIPPINGOPTIONISDEFAULT', 'SHIPPINGOPTIONAMOUNT', 'SHIPPINGOPTIONNAME');

	private function __construct() { }

	public static function getResponse(array $response) {

        $userOptions = new self();
        $userOptions->collection = new Collection(self::$allowedValues, $response);
        return $userOptions;
	}

	/**
	 * Describes how the options that were presented to the user were
	 * determined. Is one of the following values:
	 * API - Callback
	 * API - Flatrate
	 *
	 * @return string
	 */
	public function getShippingCalculationMode() {
        return $this->collection->getValue('SHIPPINGCALCULATIONMODE');
	}

	/**
	 * The Yes/No option that you chose for insurance.
	 *
	 * @return string
	 */
	public function getInsuranceOption() {
        return $this->collection->getValue('INSURANCEOPTIONSELECTED');
	}

	/**
	 * Is true if the buyer chose the default shipping option.
	 *
	 * @return boolean
	 */
	public function getDefaultShippingOption() {
        return $this->collection->getValue('SHIPPINGOPTIONISDEFAULT');
	}

	/**
	 * The shipping amount that was chosen by the buyer
	 *
	 * @return string
	 */
	public function getShippingAmount() {
        return $this->collection->getValue('SHIPPINGOPTIONAMOUNT');
	}

	/**
	 * Is true if the buyer chose the default shipping option.
	 *
	 * @return boolean
	 */
	public function getShippingOptionName() {
        return $this->collection->getValue('SHIPPINGOPTIONNAME');
	}

	public function  getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}