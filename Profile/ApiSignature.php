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

namespace PayPalNVP\Profile;

require_once 'Profile.php';

use PayPalNVP\Profile\Profile;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class ApiSignature implements Profile {

    private $userName;
    private $password;
    private $signature;
    private $subject;

    /**
     * Required parameters
     *
     * @param String $userName  obtained from paypal
     * @param String $password  obtained from paypal
     * @param String $signature obtained from paypal
     */
    public function __construct($userName, $password, $signature) {

        $this->userName = $userName;
        $this->password = $password;
        $this->signature = $signature;
        $this->subject = null;
    }

    /**
     * Email address of a PayPal account that has granted you permission to
     * make this call.
     * Set this parameter only if you are calling an API on a different
     * user's behalf
     *
     * @param String $subject
     */
    public function  setSubject($subject) {
        $this->subject = $subject;
    }

    public function  getNVPProfile() {

        $nvpProfile = array();
        $nvpProfile['USER'] = $this->userName;
        $nvpProfile['PWD'] = $this->password;
        $nvpProfile['SIGNATURE'] = $this->signature;
        if ($this->subject != null) {
            $nvpProfile['SUBJECT'] = $this->subject;
        }
        return $nvpProfile;
    }

    public function  isAPISignature() {
        return true;
    }

    public function  __toString() {
        return 'Instance of ApiCertificate class. Values: userName - '
                . $this->userName . ', password - ' . $this->password
                . ', signature - ' . $this->signature . ', subject - '
                . $this->subject;
    }

    private function  __clone() { }
}