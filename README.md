This library is written in php 5.3 and uses namespaces.

<pre>
use PayPalNVP\PayPalNVP,
    PayPalNVP\Profile\ApiSignature,
    ...
</pre>

or you can use class names with namespaces.

Every request needs profile, which is either PayPalNVP\ApiSignature, or PayPalNVP\ApiCertificate. Most of the users use ApiSignature.

<pre>
$profile = new ApiSignature('username', 'password', 'signature');
</pre>

Request are sent (and responses set) using PayPalNVP\PayPalNVP object. Which takes profile and environment as arguments. Environment has only static constructors and acts as an enum.

<pre>
$paypal = new PayPalNVP($profile, Environment::SANDBOX());
</pre>

The rest of the code depends on what request you want to make. Set express checkout would look like this:

<pre>
/* items with prices */
$items = array();
$items[] = PaymentItem::getRequest('10');
$items[] = PaymentItem::getRequest('14');

/* payment */
$payment = Payment::getRequest($items);

/* set express checkout operation */
$setEC = SetExpressCheckout::newInstance($payment, "http://your_success_url", "http://your_cancel_url");

/* request is sent and response set on $setEC */
$paypal->setResponse($setEC);

/* now you can call getter for response */
$setECResponse = $setEC->getResponse();

/* or if you prefer array of all name value pairs */
$nvpArray = $setECResponse->getResponse();

/* to check if there are any errors with the submission */
$errors = $setECResponse->getErrors();

</pre>

All operations are very similar, they use main paypal object. You need to create an instance of a request (PayPalNVP/Request package) and response is set using setResponse method on PayPalNVP. All requests have getResponse() method which returns appropriate response.