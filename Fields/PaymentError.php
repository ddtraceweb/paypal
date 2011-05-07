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
final class PaymentError implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array('SHORTMESSAGE', 'LONGMESSAGE',
		'ERRORCODE', 'SEVERITYCODE', 'ACK');

    private function __construct() { }

	public static function getResponse(array $response) {

        $error = new self();
        $error->collection = new Collection(self::$allowedValues, $response);
        return $error;
	}

	public function getErrorCode() {
        return $this->collection->getValue('ERRORCODE');
	}

	public function getShortMessage() {
        return $this->collection->getValue('SHORTMESSAGE');
	}

	public function getLongMessage() {
        return $this->collection->getValue('LONGMESSAGE');
	}

	public function getSeverityCode() {
        return $this->collection->getValue('SEVERITYCODE');
	}

	/**
	 * Appliaction-specific error values indicating more about the error
	 * condition.
	 *
	 * @return string
	 */
	public function getAck() {
        return $this->collection->getValue('ACK');
	}

	public function  getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}