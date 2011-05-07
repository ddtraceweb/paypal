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

namespace PayPalNVP\Request;

require_once 'Request.php';
require_once __DIR__ . '/../Response/GetRecurringPaymentsProfileDetailsResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\GetRecurringPaymentsProfileDetailsResponse,
    PayPalNVP\Environment,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class GetRecurringPaymentsProfileDetails implements Request {


    /** Method value of this request */
    private static $methodName = 'GetRecurringPaymentsProfileDetails';

    /** @var Collection */
    private $collection;

	/** @var GetRecurringPaymentsProfileDetailsResponse */
    private $response;


    private static $allowedValues = array('');

    /**
     * @param String $profileId Recurring payments profile ID returned in the 
     *      CreateRecurringPaymentsProfile response. Character length and 
     *      limitations: 14 single-byte alphanumeric characters. 19 character 
     *      profile IDs are supported for compatibility with previous versions 
     *      of the PayPal API.
     */
    public function  __construct($profileId) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('PROFILEID', $profileId);
		$this->nvpResponse = null;
	}


    public function getNVPRequest() {
		return $this->collection->getAllValues();
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new GetRecurringPaymentsProfileDetailsResponse($nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}


