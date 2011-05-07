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

use PayPalNVP\Response\Response,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Environment;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class SetExpressCheckoutResponse extends Response {

    /**
     * @var Collection
     */
    private $collection;

    private static $allowedValues = array('TOKEN');

	public function  __construct($response, Environment $environment) {

		parent::__construct($response, $environment);

        $this->collection = new Collection(self::$allowedValues, $this->getResponse());
	}

    /**
     * A timestamped token by which you identify to PayPal that you are
     * processing this payment with Express Checkout.
     * The token expires after three hours.If you set the token in the
     * SetExpressCheckout request, the value of the token in the response is
     * identical to the value in the request.
     * Character length and limitations: 20 single-byte characters
     *
     * @return String
     */
	public function getToken() {
        return $this->collection->getValue('TOKEN');
	}

	/**
	 * Returns redirect url for the specified environment
	 *
	 * @param Environment $environment
	 * @return String
	 */
	public function getRedirectUrl() {

        $ack = $this->getAck();
        $token = $this->getToken();

        /* ack is not successfull or token is not set */
        if (($ack == null || $ack != "Success") ||
                ($token == null || $token == "")) {

            return null;
        }

        /* return redirect url */
        return "https://www." . $this->environment->getEnvironmentPartUrl()
                . "paypal.com/cgi-bin/webscr?cmd=_express-checkout&token="
                . $token;
	}

	private function  __clone() { }
}
