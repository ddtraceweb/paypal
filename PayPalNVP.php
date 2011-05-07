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

namespace PayPalNVP;

require_once 'Profile/Profile.php';
require_once 'Request/Request.php';
require_once 'Transport/HttpPost.php';

use PayPalNVP\Profile\Profile,
    PayPalNVP\Request\Request,
    PayPalNVP\Transport\HttpPost;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class PayPalNVP {

    private static $transport = null;
    private static $version = '69.0';

	/** @var Profile */
    private $profile;
	/** @var Environment */
    private $environment;

	/**
	 *
	 * @param Profile $profile user
	 * @param Environment $environment LIVE/TEST ...
	 */
    public function  __construct(Profile $profile, Environment $environment) {

        $this->profile = $profile;
        $this->environment = $environment;
        if (self::$transport == null) {
            self::$transport = new HttpPost();
        }
    }

	/**
	 * Sends request to paypal and sets response
	 *
	 * @param Request $request
	 */
    public function setResponse(Request $request) {

		/* request data */
		$nvpString = '';
		foreach($this->profile->getNVPProfile() as $key => $value) {
			$nvpString .= $key . '=' . urlencode($value) . '&';
		}
		foreach($request->getNVPRequest() as $key => $value) {
			$nvpString .= $key . '=' . urlencode($value) . '&';
		}
		$nvpString .= 'VERSION=' . urlencode(self::$version);

        /* request url */
        $endpointUrl = 'https://';
        $endpointUrl .= ($this->profile->isAPISignature()) ? 'api-3t.' : 'api.';
        $endpointUrl .= $this->environment->getEnvironmentPartUrl();
        $endpointUrl .= 'paypal.com/nvp';

        $response = self::$transport->getResponse($endpointUrl, $nvpString);
		$request->setNVPResponse($response, $this->environment);
    }

    private function  __clone() { }
}