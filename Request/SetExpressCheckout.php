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
require_once __DIR__ . '/../Fields/Collection.php';
require_once __DIR__ . '/../Response/SetExpressCheckoutResponse.php';
require_once __DIR__ . '/../Util/ShippingOption.php';
require_once __DIR__ . '/../Util/SolutionType.php';
require_once __DIR__ . '/../Util/LandingPage.php';
require_once __DIR__ . '/../Util/ChannelType.php';
require_once __DIR__ . '/../Environment.php';

use PayPalNVP\Request\Request,
    PayPalNVP\Fields\Payment,
    PayPalNVP\Fields\Collection,
    PayPalNVP\Util\ShippingOption,
    PayPalNVP\Util\SolutionType,
    PayPalNVP\Util\LandingPage,
    PayPalNVP\Util\ChannelType,
    PayPalNVP\Fields\BillingAgreement,
    PayPalNVP\Environment,
    PayPalNVP\Response\SetExpressCheckoutResponse;

/**
 * @author pete <p.reisinger@gmail.com>
 */
final class SetExpressCheckout implements Request {

    /** Method value of this request */
    private static $methodName = 'SetExpressCheckout';

    /**
     * @var Collection
     */
    private $collection;

	/** @var SetExpressCheckoutResponse */
    private $response;

	/**
	 * payment request - up to 10 when implementing parallel payments
	 *
	 * @var array<Payment>
	 */
	private $payments;

	/**
	 * Billing Agreement Details Type Fields
	 *
	 * @var array<BillingAgreement>
	 */
	private $billingAgreement = array();

    /** @var Buyer Buyer Details Fields */
    private $buyer = null;

    /** @var FundingSource */
    private $funding = null;

    /** @var array<Shipping> */
    private $shipping = array();

    private static $allowedValues = array('TOKEN', 'MAXAMT', 'CALLBACK',
        'CALLBACKTIMEOUT', 'REQCONFIRMSHIPPING', 'NOSHIPPING', 'ALLOWNOTE',
        'ADDROVERRIDE', 'LOCALECODE', 'PAGESTYLE', 'HDRIMG', 'HDRBORDERCOLOR',
        'HDRBACKCOLOR', 'PAYFLOWCOLOR', 'EMAIL', 'SOLUTIONTYPE', 'LANDINGPAGE',
        'CHANNELTYPE', 'GIROPAYSUCCESSURL', 'GIROPAYCANCELURL',
        'BANKTXNPENDINGURL', 'BRANDNAME', 'CUSTOMERSERVICENUMBER',
        'GIFTMESSAGEENABLE', 'GIFTRECEIPTENABLE', 'GIFTWRAPENABLE',
        'GIFTWRAPNAME', 'GIFTWRAPAMOUNT', 'BUYEREMAILOPTINENABLE',
        'SURVEYQUESTION', 'CALLBACKVERSION', 'SURVEYENABLE', 'L_SURVEYCHOICE');

    /**
     * PayPal recommends that the returnUrl be the final review page on which
     * the customer confirms the order and payment or billing agreement.
     *
     * PayPal recommends that the cancelUrl be the original page on which the
     * customer chose to pay with PayPal or establish a billing agreement.
     *
	 * @param array<Payment> $payments	parallel payments - up to 10
     * @param String $returnUrl URL to which the customer's browser is returned
     *                          after choosing to pay with PayPal. Maximum 2048
     *                          characters.
     * @param String $cancelUrl URL to which the customer is returned if he
     *                          does not approve the use of PayPal to pay you.
     *                          Maximum 2048 characters.
     */
    private function  __construct(array $payments, $returnUrl, $cancelUrl) {

		$this->payments = $payments;

		$this->collection = new Collection(self::$allowedValues, null);
		$this->collection->setValue('METHOD', self::$methodName);
		$this->collection->setValue('RETURNURL', $returnUrl);
		$this->collection->setValue('CANCELURL', $cancelUrl);

		$this->nvpResponse = null;
    }

	/**
     * PayPal recommends that the returnUrl be the final review page on which
     * the customer confirms the order and payment or billing agreement.
     *
     * PayPal recommends that the cancelUrl be the original page on which the
     * customer chose to pay with PayPal or establish a billing agreement.
	 *
	 * @param Payment $payment
     * @param String $returnUrl URL to which the customer's browser is returned
     *                          after choosing to pay with PayPal. Maximum 2048
     *                          characters.
     * @param String $cancelUrl URL to which the customer is returned if he
     *                          does not approve the use of PayPal to pay you.
     *                          Maximum 2048 characters.
	 * @return SetExpressCheckout
	 */
	public static function newInstance(Payment $payment, $returnUrl,
			$cancelUrl) {

		$payments = array($payment);
		return new self($payments, $returnUrl, $cancelUrl);
	}

	/**
     * PayPal recommends that the returnUrl be the final review page on which
     * the customer confirms the order and payment or billing agreement.
     *
     * PayPal recommends that the cancelUrl be the original page on which the
     * customer chose to pay with PayPal or establish a billing agreement.
	 *
	 * @param array<Payment> $payments	parallel payments - up to 10
     * @param String $returnUrl URL to which the customer's browser is returned
     *                          after choosing to pay with PayPal. Maximum 2048
     *                          characters.
     * @param String $cancelUrl URL to which the customer is returned if he
     *                          does not approve the use of PayPal to pay you.
     *                          Maximum 2048 characters.
	 * @return SetExpressCheckout
	 */
	public static function newParallelInstance(array $payments, $returnUrl,
			$cancelUrl) {

		return new self($payments, $returnUrl, $cancelUrl);
	}

    public function setFunding(Funding $funding) {
        $this->funding = $funding;
    }

    /** @param array<Shipping> $shipping */
    public function setShipping(array $shipping) {
        $this->$shipping = $shipping;
    }

	/**
	 * Adds Billing agreement
	 *
	 * @param BillingAgreement $billingAgreement
	 */
	public function addBillngAgreement(BillingAgreement $billingAgreement) {
		$this->billingAgreement[] = $billingAgreement;
	}

	/**
	 * Sets mulitple billing agreements
	 *
	 * @param array<BillngAgreement> $billingAgreements
	 */
	public function setBillngAgreements(array $billingAgreements) {
		$this->billingAgreement = $billingAgreements;
	}

	/**
	 *
	 * @param Buyer $buyer
	 */
	public function setBuyer(Buyer $buyer) {
        $this->buyer = $buyer;
	}


    /**
     * A timestamped token, the value of which was returned by
     * SetExpressCheckout response. Character length and limitations:
     * 20 single-byte characters
     *
     * @param String $token
     */
    public function setToken($token) {
        $this->collection->setValue('TOKEN', $token);
    }

    /** The expected maximum total amount of the complete order, including
     * shipping cost and tax charges. For recurring payments, you should pass
     * the expected average transaction amount (default 25.00). PayPal uses
     * this value to validate the buyer’s funding source. If the transaction
     * includes one or more one-time purchases, this field is ignored.
     * Limitations: Must not exceed $10,000 USD in any currency.
     * No currency symbol. Must have two decimal places, decimal separator
     * must be a period (.), and the optional thousands separator must be a
     * comma (,).
     *
     * @param String $maxamt
     */
    public function setMaxAmt($maxamt) {
        $this->collection->setValue('MAXAMT', $maxamt);
    }

    /**
     * URL to which the callback request from PayPal is sent. It must start
     * with HTTPS for production integration. It can start with HTTPS or HTTP
     * for sandbox testing. Character length and limitations: 1024 characters
     *
     * @param String $callback
     */
    public function setCallback($callback) {
        $this->collection->setValue('CALLBACK', $callback);
    }

    /**
     * An override for you to request more or less time to be able to process
     * the callback request and respond. The acceptable range for the override
     * is 1 to 6 seconds. If you specify a value greater than 6, PayPal uses
     * the default value of 3 seconds.  Character length and limitations:
     * An integer between 1 and 6
     *
     * @param String $timeout
     */
    public function setCallbackTimeout($timeout) {
        $this->collection->setValue('CALLBACKTIMEOUT', $timeout);
    }

    /**
     * The value 1 indicates that you require that the customer’s shipping
     * address on file with PayPal be a confirmed address. For digital goods,
     * this field is required. You must set the value to 0.  Note: Setting this
     * field overrides the setting you have specified in your Merchant Account
     * Profile. Character length and limitations: One single-byte numeric
     * character. Allowable values: 0, 1
     *
     * @param String $required
     */
    public function setRequireConfirmShipping($required) {
        $this->collection->setValue('REQCONFIRMSHIPPING', $required);
    }

    /**
     * Determines where or not PayPal displays shipping address fields on the
     * PayPal pages. For digital goods, this field is required. You must set
     * it to NoDisplay.
     *
     * @param ShippingOption $option
     */
    public function setNoShipping(ShippingOption $option) {
        $this->collection->setValue('NOSHIPPING', $option->getValue());
    }

    /**
     * The value 1 indicates that the customer may enter a note to the merchant
     * on the PayPal page during checkout. The note is returned in the
     * GetExpressCheckoutDetails response and the DoExpressCheckoutPayment
     * response. Character length and limitations: One single-byte numeric
     * character. Allowable values: 0, 1
     *
     * @param String $allow
     */
    public function setAllowNote($allow) {
        $this->collection->setValue('ALLOWNOTE', $allow);
    }

    /**
     * The value 1 indicates that the PayPal pages should display the shipping
     * address set by you in this SetExpressCheckout request, not the shipping
     * address on file with PayPal for this customer. Displaying the PayPal
     * street address on file does not allow the customer to edit that address.
     * Character length and limitations: One single-byte numeric character.
     * Allowable values: 0, 1
     *
     * @param String $override
     */
    public function setOverrideAddress($override) {
        $this->collection->setValue('ADDROVERRIDE', $override);
    }

    /**
     * Locale of pages displayed by PayPal during Express Checkout.
     * Character length and limitations: Any two-character country code.
     * The following two-character country codes are supported by PayPal:
     * * AU - Australia
     * * AT - Austria
     * * BE - Belgium
     * * CA - Canada
     * * CH - Switzerland
     * * CN - China
     * * DE - Germany
     * * ES - Spain
     * * GB - United Kingdom
     * * FR - France
     * * IT - Italy
     * * NL - Netherlands
     * * PL - Poland
     * * US - United States
     * Any other value will default to US.
     *
     * @param Country $country
     */
    public function setLocale(Country $country) {
        $this->collection->setValue('LOCALECODE', $country->getCode());
    }

    /**
     * Sets the Custom Payment Page Style for payment pages associated with
     * this button/link. This value corresponds to the HTML variable page_style
     * for customizing payment pages. The value is the same as the Page Style
     * Name you chose when adding or editing the page style from the Profile
     * subtab of the My Account tab of your PayPal account.
     * Character length and limitations: 30 single-byte alphabetic characters
     *
     * @param String $style
     */
    public function setPageStyle($style) {
        $this->collection->setValue('PAGESTYLE', $style);
    }

    /**
     * URL for the image you want to appear at the top left of the payment
     * page. The image has a maximum size of 750 pixels wide by 90 pixels high.
     * PayPal recommends that you provide an image that is stored on a secure
     * (https) server. If you do not specify an image, the business name is
     * displayed. Character length and limit: 127 single-byte alphanumeric
     * characters
     *
     * @param String $image
     */
    public function setImage($image) {
        $this->collection->setValue('HDRIMG', $image);
    }

    /**
     * Sets the border color around the header of the payment page.
     * The border is a 2-pixel perimeter around the header space, which is 750
     * pixels wide by 90 pixels high. By default, the color is black. Character
     * length and limitation: Six character HTML hexadecimal color code in ASCII
     *
     * @param String $color
     */
    public function setHeaderBorderColor($color) {
        $this->collection->setValue('HDRBORDERCOLOR', $color);
    }

    /**
     * Sets the background color for the header of the payment page.
     * By default, the color is white. Character length and limitation: Six
     * character HTML hexadecimal color code in ASCII
     *
     * @param String $color
     */
    public function setHeaderBackgroundColor($color) {
        $this->collection->setValue('HDRBACKCOLOR', $color);
    }

    /**
     * Sets the background color for the payment page. By default, the color is
     * white. Character length and limitation: Six character HTML hexadecimal
     * color code in ASCII
     *
     * @param String $color
     */
    public function setBackgroundColor($color) {
        $this->collection->setValue('PAYFLOWCOLOR', $color);
    }

    /**
     * Email address of the buyer as entered during checkout. PayPal uses this
     * value to pre-fill the PayPal membership sign-up portion of the PayPal
     * login page. Character length and limit: 127 single-byte alphanumeric
     * characters
     *
     * @param String $email
     */
    public function setEmail($email) {
        $this->collection->setValue('EMAIL', $email);
    }

    /**
     * Type of checkout flow
     * Note: You can pass Mark to selectively override the PayPal Account
     * Optional setting if PayPal Account Optional is turned on in your
     * merchant account. Passing Sole has no effect if PayPal Account Optional
     * is turned off in your account
     *
     * @param SolutionType $flow
     */
    public function setCheckoutFlow(SolutionType $type) {
        $this->collection->setValue('SOLUTIONTYPE', $type->getValue());
    }

    /**
     * Type of PayPal page to display
     *
     * @param LandingPage $page
     */
    public function setLandingPage(LandingPage $page) {
        $this->collection->setValue('LANDINGPAGE', $page->getValue());
    }

    /**
     * Type of channel
     *
     * @param ChannelType $channel
     */
    public function setChannelType(ChannelType $channel) {
        $this->collection->setValue('CHANNELTYPE', $channel->getValue());
    }

    /**
     * The URL on the merchant site to redirect to after a successful giropay
     * payment. Use this field only if you are using giropay or bank transfer
     * payment methods in Germany.
     *
     * @param String $url
     */
    public function setGiroSuccessUrl($url) {
        $this->collection->setValue('GIROPAYSUCCESSURL', $url);
    }

    /**
     * The URL on the merchant site to redirect to after a un-successful giropay
     * payment. Use this field only if you are using giropay or bank transfer
     * payment methods in Germany.
     *
     * @param String $url
     */
    public function setGiroCancelUrl($url) {
        $this->collection->setValue('GIROPAYCANCELURL', $url);
    }

    /**
     * The URL on the merchant site to transfer to after a bank transfer
     * payment. Use this field only if you are using giropay or bank transfer
     * payment methods in Germany.
     *
     * @param String $url
     */
    public function setGiroPendingUrl($url) {
        $this->collection->setValue('BANKTXNPENDINGURL', $url);
    }

    /**
     * A label that overrides the business name in the PayPal account on the
     * PayPal hosted checkout pages. Character length and limitations: 127
     * single-byte alphanumeric characters
     *
     * @param String $name
     */
    public function setBrandingName($name) {
        $this->collection->setValue('BRANDNAME', $name);
    }

    /**
     * Merchant Customer Service number displayed on the PayPal Review page.
     * Limitations: 16 single-byte characters
     *
     * @param String $number
     */
    public function setCustomerServiceNumber($number) {
        $this->collection->setValue('CUSTOMERSERVICENUMBER', $number);
    }

    /**
     * Enable gift message widget on the PayPal Review page. Allowable values
     * are 0 and 1
     *
     * @param String $message
     */
    public function setGiftMessage($message) {
        $this->collection->setValue('GIFTMESSAGEENABLE', $message);
    }

    /**
     * Enable gift receipt widget on the PayPal Review page. Allowable values
     * are 0 and 1
     *
     * @param String $receipt
     */
    public function setGiftReceipt($receipt) {
        $this->collection->setValue('GIFTRECEIPTENABLE', $receipt);
    }

    /**
     * Enable gift wrap widget on the PayPal Review page. Note: If the value 1
     * is passed for this field values for the gift wrap amount and gift wrap
     * name are not passed, the gift wrap name will not be displayed and the
     * gift wrap amount will display as 0.00. Allowable values are 0 and 1
     *
     * @param String $wrap
     */
    public function setGiftWrap($wrap) {
        $this->collection->setValue('GIFTWRAPENABLE', $wrap);
    }

    /**
     * Label for the gift wrap option such as "Box with ribbon".
     * Limitations: 25 single-byte characters
     *
     * @param String $name
     */
    public function setGiftWrapName($name) {
        $this->collection->setValue('GIFTWRAPNAME', $name);
    }

    /**
     * Label for the gift wrap option such as "Blue box with ribbon".
     * Limitations: Must not exceed $10,000 USD in any currency. No currency
     * symbol. Must have two decimal places, decimal separator must be a
     * period (.), and the optional thousands separator must be a comma (,).
     *
     * @param String $
     */
    public function setGiftWrapAmount($amount) {
        $this->collection->setValue('GIFTWRAPAMOUNT', $amount);
    }

    /**
     * Enable buyer email opt-in on the PayPal Review page. Allowable values
     * are 0 and 1
     *
     * @param String $optIn
     */
    public function setEmailOptIn($optIn) {
        $this->collection->setValue('BUYEREMAILOPTINENABLE', $optIn);
    }

    /**
     * Text for the survey question on the PayPal Review page. If the survey
     * question is present, at least 2 survey answer options need to be present.
     * Limitations: 50 single-byte characters
     *
     * @param String $question
     */
    public function setSurveyQuestion($question) {
        $this->collection->setValue('SURVEYQUESTION', $question);
    }

    /**
     * The version of the Instant Update API that your callback server uses.
     * The default is the current version.
     *
     * @param String $version
     */
    public function setCallbackVersion($version) {
        $this->collection->setValue('CALLBACKVERSION', $version);
    }

    /**
     * Enable survey functionality. Allowable values are 0 and 1
     *
     * @param String $survey
     */
    public function setSurvey($survey) {
        $this->collection->setValue('SURVEYENABLE', $survey);
    }

    /**
     * Possible options for the survey answers on the PayPal Review page.
     * Answers are displayed only if a valid survey question is present.
     * Limitations: 15 single-byte characters
     * Array has to be indexed (from 0)
     *
     * @param array $choices
     */
    public function setSurveyChoices(array $choices) {

        foreach($choices as $index => $choice) {
            $this->collection->setValue('L_SURVEYCHOICE' . $index, $choice);
        }
    }

    /**
     * @return array
     */
    public function getNVPRequest() {

		$request = $this->collection->getAllValues();

		/* payment */
		foreach($this->payments as $index => $payment) {
			foreach($payment->getNVPArray() as $key => $value) {
				if (is_array($value)) {	// payment item is array and has to start with L_
					foreach($value as $itemIndex => $item) {
						foreach($item as $k => $v) {
							$request['L_PAYMENTREQUEST_' . $index . '_' . $k . $itemIndex] = $v;
						}
					}
				} else {
					$request['PAYMENTREQUEST_' . $index . '_' . $key] = $value;
				}
			}
		}
		/* billing agreement */
		foreach($this->billingAgreement as $index => $billingAgreement) {
			foreach($billingAgreement->getNVPArray() as $key => $value) {
				$request['L_' . $key . $index] = $value;
			}
		}

		/* shipping options */
		foreach($this->shipping as $index => $shipping) {
			foreach($shipping->getNVPArray() as $key => $value) {
				$request['L_' . $key . $index] = $value;
			}
		}

        /* buyer and funding */
        if ($this->buyer != null) {
            $request = array_merge($request, $this->buyer->getNVPArray());
        }
        if ($this->funding != null) {
            $request = array_merge($request, $this->funding->getNVPArray());
        }

		return $request;
    }

    public function setNVPResponse($nvpResponse, Environment $environment) {
		$this->response = new SetExpressCheckoutResponse($nvpResponse, $environment);
    }

    /**
     * @return SetExpressCheckoutResponse
     */
    public function getResponse() {
        return $this->response;
    }
}
