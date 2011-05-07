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
 * The action to be performed to the recurring payments profile.
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class ProfileAction {

    private $action;

    private function __construct($action) {
        $this->action = $action;
    }

    /** 
     * Only profiles in Active or Suspended state can be canceled.
     * 
     * @return ProfileAction
     */
    public static function getCancel() {
        return new self('Cancel');
    }

    /** 
     * Only profiles in Active state can be suspended.
     * 
     * @return ProfileAction
     */
    public static function getSuspend() {
        return new self('Suspend ');
    }

    /** 
     * Only profiles in a suspended state can be reactivated.
     * 
     * @return ProfileAction
     */
    public static function getReactivate() {
        return new self('Reactivate ');
    }

    public function getValue() {
        return $this->action;
    }

    private function __clone() {}
}





