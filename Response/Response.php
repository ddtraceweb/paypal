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

require_once 'Error.php';
require_once __DIR__ . '/../Environment.php';

use PayPalNVP\Response\Error,
    PayPalNVP\Environment;

/**
 * @author pete <p.reisinger@gmail.com>
 */
abstract class Response {

	/** @var array response from paypal */
	protected $response;

	/** @var array<Error> */
	protected $errors;

    /** @var Environment */
	protected $environment;

    /**
     *
     * @param String $response response string from paypal
     * @param Environment $environment
     */
	protected function  __construct($response, Environment $environment) {

        $allowedErrors = array('ERRORCODE', 'SHORTMESSAGE', 'LONGMESSAGE',
            'SEVERITYCODE');

        $this->environment = $environment;
		$this->errors = array();
		$errors = array();

		$response = explode('&', $response);
		$decodedResponse = array();
		foreach ($response as $item) {

			$keyVal = explode('=', $item);
            $key = $keyVal[0];
            $value = $keyVal[1];

			$keyParts = explode('_', $key);
			if (!empty($keyParts)) {
				if ($keyParts[0] == 'L' && count($keyParts) > 1) {

                    $x = strtoupper($keyParts[1]);
                    preg_match('/[\d]+$/', $x, $matches);
                    if (count($matches) == 1) {
                        $size = strlen($x);
                        $index = $matches[0];
                        $indexSize = strlen($index);
                        $v = substr($x, 0, $size - $indexSize);
                        if (in_array($v, $allowedErrors)) {
                            $errors[$index][$v] = urldecode($value);
                        }
                    }
                }
            }
			$decodedResponse[$key] = urldecode($value);
		}

		/* set response */
		$this->response = $decodedResponse;
		/* set errors - objects */
		foreach($errors as $key => $value) {
			$this->errors[$key] = new Error($value);
		}
	}

    /**
     * Acknowledgement status, which is one of the following values:
     * Success indicates a successful operation
     * SuccessWithWarning indicates a successful operation; however, there
     *      are messages returned in the response that you should examine
     * Failure indicates that the operation failed; the response will also
     *      contain one or more error message explaining the failure.
     * FailureWithWarning indicates that the operation failed and that there
     * are messages returned in the response that you should examine
     *
     * @return String
     */
	public function getAck() {
		return (isset($this->response['ACK'])) ? $this->response['ACK'] : null;
	}

    /**
     * The date and time that the requested API operation was performed
     *
     * @return String
     */
	public function getTimeStamp() {
		return (isset($this->response['TIMESTAMP']))
				? $this->response['TIMESTAMP'] : null;
	}

    /**
     * Correlation ID, which uniquely identifies the transaction to PayPal
     *
     * @return String
     */
	public function getCorrelationId() {
		return (isset($this->response['CORRELATIONID']))
				? $this->response['CORRELATIONID'] : null;
	}

    /**
     * The version of the API
     *
     * @return String
     */
	public function getVersion() {
		return (isset($this->response['VERSION']))
				? $this->response['VERSION'] : null;
	}

    /**
     * The sub-version of the API
     *
     * @return String
     */
	public function getBuild() {
		return (isset($this->response['BUILD']))
				? $this->response['BUILD'] : null;
	}

	/**
	 * @return decoded response from paypal as array
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Return array of Error object or empty array if no error was received
	 *
	 * @return array<Error>
	 */
	public function getErrors() {
		return $this->errors;
	}
}
