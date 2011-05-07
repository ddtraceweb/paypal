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

require_once __DIR__ . '/../Environment.php';

use PayPalNVP\Environment;

/**
 * @author pete <p.reisinger@gmail.com>
 */
interface Request {

    /**
     * Creates and returns part of the nvp (name value pair) request containing
     * request values
     *
     * @return array<String, String>
     */
    public function getNVPRequest();

    /**
     * Setter (used by PayPalNVP class) to set response from paypal
     *
     * @param String $nvpResponse       response from paypal
     * @param Environment $environment  environment used to send the request
     * @return array<String, String>
     */
    public function setNVPResponse($nvpResponse, Environment $environment);

    /**
     * Returns response from paypal as Object. Call this method after sending
     * request to payapl ($payPalNVP->getResponse($request)). If response is
     * not set/received, returns null.
     *
     * @return Response
     */
    public function getResponse();
}