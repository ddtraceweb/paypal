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
final class RecurringPaymentsSummary implements Field {

    /**
     * @var Collection
     */
    private $collection;

    /** @var array values allowed in response */
	private static $allowedValues = array('NEXTBILLINGDATE', 
        'NUMCYCYLESCOMPLETED', 'NUMCYCLESREMAINING', 'OUTSTANDINGBALANCE', 
        'FAILEDPAYMENTCOUNT', 'LASTPAYMENTDATE', 'LASTPAYMENTAMT');

    private function __construct() { }

    /**
     * @param array $response nvp response represented as an array, array needs
     * to contain only keys without 'L_' prefix and 'n' suffix.
     * @return RecurringPaymentsSummary as response
     */
    public static function getResponse(array $response) {

        $summary = new self();
        $summary->collection = new Collection(self::$allowedValues, $response);
        return $summary;
    }

    /**
     * @return String the next scheduled billing date, in YYYY-MM-DD format
     */
	public function getNextBillingDate() {
		return $this->collection->getValue('NEXTBILLINGDATE');
	}

    /**
     * @return String the number of billing cycles completed in the current 
     *      active subscription period. A billing cycle is considered completed 
     *      when payment is collected or after retry attempts to collect 
     *      payment for the current billing cycle have failed.
     */
	public function getNumberCyclesCompleted() {
		return $this->collection->getValue('NUMCYCYLESCOMPLETED');
	}

    /**
     * @return string the number of billing cycles remaining in the current 
     *      active subscription period.
     */
	public function getNumberCyclesRemaining() {
		return $this->collection->getValue('NUMCYCLESREMAINING');
	}

    /**
     * @return string the current past due or outstanding balance for this 
     *      profile.
     */
	public function getOutstandingBalance() {
		return $this->collection->getValue('OUTSTANDINGBALANCE');
	}

    /**
     * @return type the total number of failed billing cycles for this profile.
     */
	public function getFailedPaymentCount() {
		return $this->collection->getValue('FAILEDPAYMENTCOUNT');
	}

    /**
     * @return type the date of the last successful payment received for this 
     *      profile, in YYYY-MM-DD format.
     */
	public function getLastPaymentDate() {
		return $this->collection->getValue('LASTPAYMENTDATE');
	}

    /**
     * @return type the amount of the last successful payment received for this profile.
     */
	public function getLastPaymentAmount() {
		return $this->collection->getValue('LASTPAYMENTAMT');
	}

    /**
     * @return array
     */
    public function getNVPArray() {
        return $this->collection->getAllValues();
    }

	private function  __clone() { }
}

