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
final class Buyer implements Field {

    /**
     * @var Collection
     */
	private $collection;

    private static $allowedValues = array('BUYERID', 'BUYERUSERNAME',
        'BUYERREGISTRATIONDATE');

	private function  __construct() { }

    public static function getReqeuest() {

        $buyer = new self();
        $buyer->collection = new Collection(self::$allowedValues, null);
        return $buyer;
    }

	/**
	 * The unique identifier provided by eBay for this buyer. The value may
	 * or may not be the same as the username. In the case of eBay, it is
	 * different. Character length and limitations: 255 single-byte
	 * characters
	 *
	 * @param String $id
	 */
    public function setId($id) {
        $this->collection->setValue('BUYERID', $id);
    }

	/**
	 * The user name of the user at the marketplaces site.
	 *
	 * @param String $userName
	 */
    public function setUsername($username) {
        $this->collection->setValue('BUYERUSERNAME', $username);
    }

	/**
	 * Date when the user registered with the marketplace.
	 *
	 * @param string $date - xs:dateTime
	 */
    public function setRegistrationDate($registrationDate) {
        $this->collection->setValue('BUYERREGISTRATIONDATE', $registrationDate);
    }

	public function getNVPArray() {
		return $this->collection->getAllValues();
	}

	private function  __clone() { }
}
