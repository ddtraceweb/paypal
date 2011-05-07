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
require_once __DIR__ . '/../Response/ManageRecurringPaymentsProfileStatusResponse.php';
require_once __DIR__ . '/../Util/ProfileAction.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\ManageRecurringPaymentsProfileStatusResponse,
    PayPalNVP\Util\ProfileAction,
    PayPalNVP\Environment,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class ManageRecurringPaymentsProfileStatus implements Request {

    /** Method value of this request */
    private static $methodName = 'ManageRecurringPaymentsProfileStatus';

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
     * @param ProfileAction $action the action to be performed to the recurring 
     *      payments profile.
     */
    public function  __construct($profileId, ProfileAction $action) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('PROFILEID', $profileId);
		$this->collection->setValue('ACTION', $action->getValue());
		$this->nvpResponse = null;
	}

    /**
     * @param string $note the reason for the change in status. For profiles 
     *      created using Express Checkout, this message will be included in 
     *      the email notification to the buyer when the status of the profile 
     *      is successfully changed, and can also be seen by both you and the 
     *      buyer on the Status History page of the PayPal account. 
     */
    public function setNote($note) {
		$this->collection->setValue('NOTE', $note);
    }

    public function getNVPRequest() {
		return $this->collection->getAllValues();
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new ManageRecurringPaymentsProfileStatusResponse($nvpResponse, $environment);
    }

    /**
     * @return ManageRecurringPaymentsProfileStatusResponse 
     */
    public function getResponse() {
        return $this->response;
    }
}



