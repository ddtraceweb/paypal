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
 * Type of channel
 * 
 * @author pete <p.reisinger@gmail.com>
 */
final class ChannelType {

    private $channelType;

    private function __construct($channelType) {
        $this->channelType = $channelType;
    }

    /**
     * non-auction seller
     * @return ChannelType
     */
    public static function getMerchant() {
        return new self('Merchant');
    }

    /**
     * eBay auction
     * @return ChannelType
     */
    public static function getEbayItem() {
        return new self('eBayItem');
    }

    public function getValue() {
        return $this->channelType;
    }

    private function __clone() {}
}



