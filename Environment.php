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

namespace PayPalNVP;

/**
 * paypal environment - live, sandbox or beta sandbox
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class Environment {

    /**
     * Holds instances for lazy initialization.
     *
     * @var array<Environemnt>
     */
    private static $instances = array();

    /**
     *
     * @var string enum id - name of the static method that returned instance
     */
    private $name;

    /** string representaion of the environment part url */
    private $environment;

    private function __construct($name, $environment) {
        $this->name = $name;
        $this->environment = $environment;
    }

    /**
     * live environment
     *
     * @return self
     */
    public static function LIVE() {

        $name = 'LIVE';
        $environment = '';
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name, $environment);
        }
        return self::$instances[$name];
    }

    /**
     * test environment
     *
     * @return self
     */
    public static function SANDBOX() {

        $name = 'SANDBOX';
        $environment = 'sandbox.';
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name, $environment);
        }
        return self::$instances[$name];
    }

    /**
     * beta test environment
     *
     * @return self
     */
    public static function BETA_SANDBOX() {

        $name = 'BETA_SANDBOX';
        $environment = 'beta-sandbox.';
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name, $environment);
        }
        return self::$instances[$name];
    }

    /**
     * This method is used only by PayPalNVP class
     *
     * @return String   part of the url that inidicates which environment shoud
     *                  be used
     */
    public function getEnvironmentPartUrl() {
        return $this->environment;
    }

    public function __toString() {
        return $this->name;
    }

    private function __clone() {}
}