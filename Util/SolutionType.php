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
 * Type of checkout flow
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class SolutionType {

    private $solutionType;

    private function __construct($solutionType) {
        $this->solutionType = $solutionType;
    }

    /**
     * Buyer does not need to create a PayPal account to check out.
     * This is referred to as PayPal Account Optional.
     * @return SolutionType
     */
    public static function getSole() {
        return new self('Sole');
    }

    /**
     * Buyer must have a PayPal account to check out.
     * @return SolutionType
     */
    public static function getMark() {
        return new self('Mark');
    }

    public function getValue() {
        return $this->solutionType;
    }

    private function __clone() {}
}

