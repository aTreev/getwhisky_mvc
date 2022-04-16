<?php

use Getwhisky\Controllers\CheckoutController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

$page = new Page();
// Redirect if logged in
if ($page->getUser()->getAccessLevel() > 0) header("Location: /checkout/");

// Create new checkout
$checkout = new CheckoutController(null, $page->getCart());

echo $page->displayPage($checkout->getView()->checkoutRegistrationPage());


?>