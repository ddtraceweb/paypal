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
final class Secure3d implements Field {

    /**
     * @var Collection
     */
	private $collection;

	private static $allowedValues = array('AUTHSTATUS3DS', 'MPIVENDOR3DS',
        'CAVV', 'ECI3DS', 'XID');

    private function __construct() { }

	public static function getResponse(array $response) {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, $response);
	}

	public static function getRequest() {

        $info = new self();
        $info->collection = new Collection(self::$allowedValues, null);
	}

	/**
	 * @return string a value returned by the Cardinal Centinel.
	 */
	public function getStatus() {
        return $this->collection->getValue('AUTHSTATUS3DS');
	}

	/**
	 * @param string a value returned by the Cardinal Centinel. If the
     *      cmpi_lookup request returns Y for Enrolled, set this field to the
     *      PAResStatus value returned by cmpi_authenticate; otherwise, set
     *      this field to blank.
	 */
	public function setStatus($status) {
        $this->collection->setValue('AUTHSTATUS3DS', $status);
	}

	/**
	 * @return string a value returned by the Cardinal Centinel.
	 */
	public function getVendor() {
        return $this->collection->getValue('MPIVENDOR3DS');
	}

	/**
	 * @param string a value returned by the Cardinal Centinel. If the
     *      cmpi_lookup request returns Y for Enrolled, set this field to the
     *      Cavv value returned by cmpi_authenticate; otherwise, set this field
     *      to blank.
	 */
	public function setCavv($cavv) {
        $this->collection->setValue('CAVV', $cavv);
	}

	/**
	 * @return string a value returned by the Cardinal Centinel.
	 */
	public function getCavv() {
        return $this->collection->getValue('CAVV');
	}

	/**
	 * @param string a value returned by the Cardinal Centinel. If the
     *      cmpi_lookup request returns Y for Enrolled, set this field to the
     *      EciFlag value returned by cmpi_authenticate; otherwise, set this
     *      field to the EciFlag value returned by cmpi_lookup.
	 */
	public function setEci($eci) {
        $this->collection->setValue('ECI3DS', $eci);
	}

	/**
	 * @return string a value returned by the Cardinal Centinel.
	 */
	public function getEci() {
        return $this->collection->getValue('ECI3DS');
	}

	/**
	 * @param string a value returned by the Cardinal Centinel. If the
     *      cmpi_lookup request returns Y for Enrolled, set this field to the
     *      Xid value returned by cmpi_authenticate; otherwise set this field
     *      to blank.
	 */
	public function setEci($xid) {
        $this->collection->setValue('XID', $xid);
	}

	/**
	 * @return string a value returned by the Cardinal Centinel.
	 */
	public function getEci() {
        return $this->collection->getValue('XID');
	}

	/**
	 * @param string a value returned by the Cardinal Centinel. Set this field
     *      to the Enrolled value returned by cmpi_lookup.
	 */
	public function setVendor($status) {
        $this->collection->setValue('MPIVENDOR3DS', $status);
	}

	public function  getNVPArray() {
        return $this->collection->getAllValues();
	}

	private function __clone() {}
}

