<?php

use Getwhisky\Controllers\AddressController;
use Getwhisky\Controllers\CartController;
use Getwhisky\Controllers\OrderController;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
require_once("$path/src/constants.php");
require_once("$path/src/stripe/init.php");


\Stripe\Stripe::setApiKey(constant("stripe_sk"));


// You can find your endpoint's secret in your webhook settings
$endpoint_secret = constant("stripe_webhook_secret");

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}


// Handle the checkout.session.completed event
if ($event->type == 'checkout.session.completed') {
  $session = $event->data->object;
  // Fulfill the purchase...
  fulfill_order($session);
}


/**************
 * Creates an order on the backend database
 * Uses application ids passed through Stripe metadata after a successful payment
 * Creates an order
 * adds items to the order
 * Emails the customer using the user objects email
 */
function fulfill_order($session)
{
  // Retrieve required data from stripe metadata
  $userid = $session->metadata->userid;
  $addressid = $session->metadata->addressid;
  $orderid = $session->metadata->orderid;
  $deliveryCost = $session->metadata->deliveryCost;
  // Get stripe payment_intent
  $paymentIntent = $session->payment_intent;

  // get the User's delivery address
  $address = new AddressController();
  $address->initAddressById($addressid, $userid);

  // Get the user's cart
  $cart = new CartController();
  $cart->initCart($userid);

  // Create new order
  $order = new OrderController();
  $order->createOrder($orderid, $paymentIntent, $userid, $cart->getCartTotal(), $deliveryCost, $address->getRecipientName(), $address->getLine1(), $address->getLine2(), $address->getCity(), $address->getCounty(), $address->getPostcode());

  // Add cart items to order
  foreach($cart->getItems() as $item) {
    $order->addItemToOrder($item->getProduct()->getId(), $item->getProduct()->getName(), $item->getProduct()->getImage(), $item->getProduct()->getActivePrice(), $item->getQuantity());
  }

  // email user

  // empty cart
  $cart->checkout();
}
?>