<?php
use Getwhisky\Controllers\CheckoutController;
use Getwhisky\Controllers\OrderController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

// Logic start
$page = new Page(2);

// Check for user's order
$orderContr = new OrderController();
$orderData = $orderContr->getMostRecentOrder($page->getUser()->getId());

// Check if placed within last hour
// (Page expiry set to 1 hour)
if ($orderData && (time() - strtotime($orderData[0]['date_placed']) < 3600)) {
    // initialize order
    $orderContr->initOrder($orderData[0]['id'], $page->getUser()->getId());
    
    // create checkout and display confirmation
    $checkout = new CheckoutController($page->getUser(), $page->getCart(), $orderContr);
    echo $page->displayPage($checkout->getView()->orderConfirmationPage()); 
} else {
    header("Location: /");
}



?>
