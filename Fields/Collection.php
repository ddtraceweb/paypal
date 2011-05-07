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

/**
 * Acts as an Collection for nvp pairs
 * All keys are in upper case.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class Collection {

	/** @var array<String, String> holds name value pair */
	private $nvp;

    /**
     * If second argument is not specified or is null, then this collection is
     * initialieze as empty
     *
     * @param array $allowedValues array of keys allowed in response
     * @param array $response nvp response as array
     */
	public function __construct(array $allowedValues, array $response = null) {

        /* convert response to upper case - paypal is inconsistent */
        $upperResponse = array();
        if ($response != null) {
            foreach($response as $key => $value) {
                $key = strtoupper($key);
                $upperResponse[$key] = $value;
            }
        }

		$this->nvp = array();

		/* sets only values for this response field */
        if (!empty($upperResponse)) {
            foreach($allowedValues as $key) {
                $key = strtoupper($key);
                if (isset($upperResponse[$key])) {
                    $this->nvp[$key] = $upperResponse[$key];
                }
            }
        }
	}

    /**
     * @param string $key
     * @return string or null if the key is not set
     */
    public function getValue($key) {
		return (isset($this->nvp[$key])) ? $this->nvp[$key] : null;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setValue($key, $value) {
        $this->nvp[$key] = $value;
    }

    /**
     * @return array
     */
	public function getAllValues() {
		return $this->nvp;
	}

    private function __clone() {}
}