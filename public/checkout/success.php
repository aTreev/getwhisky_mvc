<?php
use Getwhisky\Controllers\CheckoutController;
use Getwhisky\Controllers\OrderController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

// Logic start
$page = new Page(2);

$orderid = (Util::valStr($_GET['order'])) ? Util::sanStr($_GET['order']) : "-1";
// Check for order
$order = new OrderController;
$exists = $order->initOrder($orderid, $page->getUser()->getId());

if ($exists) {
    $checkout = new CheckoutController($page->getUser(), $page->getCart(), $order);
    echo $page->displayPage($checkout->getView()->orderConfirmationPage()); 
}

?>
