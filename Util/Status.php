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

namespace PayPalNVP\Util;

/**
 * Status of the recurring payment profile.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class Status {

    private $status;

    private function __construct($status) {
        $this->status = $status;
    }

    /**
     * The recurring payment profile has been successfully created and
     * activated for scheduled payments according the billing instructions from
     * the recurring payments profile.
     *
     * @return Status
     */
    public static function getActive() {
        return new self('ActiveProfile');
    }

    /**
     * The system is in the process of creating the recurring payment profile.
     * Please check your IPN messages for an update.
     *
     * @return Status
     */
    public static function getPending() {
        return new self('PendingProfile');
    }

    public function getValue() {
        return $this->status;
    }

    private function __clone() {}
}





