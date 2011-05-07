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
require_once __DIR__ . '/../Response/RefundTransactionResponse.php';
require_once __DIR__ . '/../Environment.php';
require_once __DIR__ . '/../Util/RefundType.php';
require_once __DIR__ . '/../Fields/Collection.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Response\RefundTransactionResponse,
    PayPalNVP\Environment,
    PayPalNVP\Util\RefundType,
    PayPalNVP\Fields\Collection;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class RefundTransaction implements Request {

    /** Method value of this request */
    private static $methodName = 'RefundTransaction';

    /**
     * @var Collection
     */
    private $collection;

	/** @var RefundTransactionResponse */
    private $response;

    private static $allowedValues = array();

    /**
     * @param String $transactionId Unique identifier of a transaction. 
     *      Character length and limitations: 17 single-byte alphanumeric 
     *      characters.
     */
    public function  __construct($transactionId) {

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('TRANSACTIONID', $transactionId);
		$this->nvpResponse = null;
	}

    /**
     * @param type $note Custom memo about the refund. Character length and 
     *      limitations: 255 single-byte alphanumeric characters. 
     */
    public function setNote($note) {
        $this->collection->setValue('NOTE', $note);
    }

    /**
     * @param type $id your own invoice or tracking number. Character length 
     *      and limitations: 127 single-byte alphanumeric characters
     */
    public function setInvoiceId($id) {
        $this->collection->setValue('INVOICEID', $id);
    }

    /**
     * @param type $amount Refund amount. Amount is required if RefundType is 
     *      Partial. Note: If RefundType is Full, do not set the Amount.
     */
    public function setAmount($amount) {
        $this->collection->setValue('AMT', $amount);
    }

    /**
     * @param Currency $currency this field is required for partial refunds. 
     *      Do not use this field for full refunds.
     */
    public function setCurrency(Currency $currency) {
        $this->collection->setValue('CURRENCYCODE', $currency->getCode());
    }

    /**
     * @param RefundType $type defaults to Full
     */
    public function setRefundType(RefundType $type) {
        $this->collection->setValue('REFUNDTYPE', $type->getCode());
    }

    public function getNVPRequest() {
		return $this->collection->getAllValues();
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {

		$this->response = new RefundTransactionResponse(
                $nvpResponse, $environment);
    }

    public function getResponse() {
        return $this->response;
    }
}


