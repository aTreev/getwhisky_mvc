<?php

use Getwhisky\Controllers\AddressController;
use Getwhisky\Controllers\OrderController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\UniqueIdGenerator;
use Getwhisky\Util\Util;


$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
require_once("$path/src/constants.php");
require_once("$path/src/stripe/init.php");

// addressid checks
if (!isset($_POST['address-id'])  || !Util::valStr($_POST['address-id'])) die("address not supplied");

$addressController = new AddressController();
$addressid = Util::sanStr($_POST['address-id']);
$page = new Page(2, true);

// Check that the address exists and belongs to the user
$found = $addressController->initAddressById($addressid, $page->getUser()->getId());

// error message if not user's address
if (!$found) die("Invalid address supplied");

// Generate an orderid here, pass it as metadata to the stripe webhook and use it as a get parameter in the success page
//$orderid = (new UniqueIdGenerator())->properties((new OrderController())->getOrderIds(), 10)->getUniqueId();
startCheckout($addressid, $page);







function startCheckout($addressid, $page)
{
    $deliveryCharge = 0;
    $freeDeliveryThreshold = constant("free_delivery_threshold");
    $line_items = [];

    // Construct stripe line_items from cart items
    foreach($page->getCart()->getItems() as $item) {
        array_push($line_items, ['price_data' => ['currency' => 'gbp', 'product_data' => ['name' => ucwords($item->getProduct()->getName()),],'unit_amount' => $item->getProduct()->getActivePrice()*100,],'quantity' => $item->getQuantity(),]);
    }
    // add delivery fee if basket total less than threshold
    if ($page->getCart()->getCartTotal() < $freeDeliveryThreshold) {
        $deliveryCharge = constant("delivery_cost");
        array_push($line_items,['price_data' => ['currency' => 'gbp', 'product_data' => ['name' => "Delivery fee",],'unit_amount' => $deliveryCharge*100,],'quantity' => 1,]);
    }

    if (empty($line_items)) header("Location: /basket");

    // Stripe logic
    \Stripe\Stripe::setApiKey(constant("stripe_sk"));
    header('Content-Type: application/json');
    $DOMAIN = constant("domain_name");
    $checkout_session = \Stripe\Checkout\Session::create([
        'billing_address_collection' => 'required',
        'line_items' => [$line_items],
        'payment_method_types' => [
            'card',
        ],
        'mode' => 'payment',
        'metadata' => [
            'userid' => $page->getUser()->getId(),
            'addressid' => $addressid,
            'deliveryCost' => $deliveryCharge
        ],
        'success_url' => $DOMAIN . "/checkout/success",
        'cancel_url' => $DOMAIN . '/basket',
        ]);
    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
}
?>