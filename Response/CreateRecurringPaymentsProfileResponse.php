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
require_once __DIR__ . '/../Util/Status.php';

use PayPalNVP\Response\Response,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Environment,
    PayPalNVP\Util\Status;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class CreateRecurringPaymentsProfileResponse extends Response {

    /**
     * @var Collection
     */
    private $collection;

    private static $allowedValues = array('PROFILEID', 'STATUS');

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);

        $this->collection = new Collection(self::$allowedValues, $this->getResponse());
	}

    /**
     * A unique identifier for future reference to the details of this
     * recurring payment. Character length and limitations:
     * Up to 14 single-byte alphanumeric characters.
     *
     * @return String
     */
	public function getProfileId() {
        return $this->collection->getValue('PROFILEID');
	}

    /**
     * Status of the recurring payment profile.
     *
     * @return Status
     */
	public function getStatus() {

        $value = $this->collection->getValue('STATUS');

        if ($value == null) {
            return null;
        }

        switch ($value) {
            case 'ActiveProfile':
                return Status::getActive();
                break;
            case 'PendingProfile':
                return Status::getPending();
                break;
        }

        return null;
	}

	private function  __clone() { }
}

