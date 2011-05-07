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
require_once __DIR__ . '/../Response/GetExpressCheckoutDetailsResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Environment,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Response\GetExpressCheckoutDetailsResponse;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class GetExpressCheckoutDetails implements Request {

    /** Method value of this request */
    private static $methodName = 'GetExpressCheckoutDetails';

    /**
     * @var Collection
     */
    private $collection;

	/** @var GetExpressCheckoutDetailsResponse */
    private $response;

    private static $allowedValues = array('METHOD', 'TOKEN', 'PAYERID');

    /**
     * @param String $queryString from paypal response
     */
    public function  __construct($queryString) {

        $response = array();

        /* make sure that ? is not passed */
        $url = explode("?", $queryString);
        $index = count($url) - 1;
        $queryString = $url[$index];

        $parts = explode("&", $queryString);
        foreach($parts as $part) {
            $values = explode("=", $part);
            $response[$values[0]] = $values[1];
        }

        $this->collection = new Collection(self::$allowedValues, $response);
		$this->collection->setValue('METHOD', self::$methodName);
	}

    public function getNVPRequest() {
	 return $this->collection->getAllValues();
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new GetExpressCheckoutDetailsResponse($nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}
