<?php

use Getwhisky\Controllers\CheckoutController;
use Getwhisky\Controllers\Page;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

// Set page to 0 to allow manual redirection
$page = new Page();
if ($page->getUser()->getAccessLevel() < 2) header("Location: /checkout/register");
if (empty($page->getCart()->getItems())) header("Location: /basket/");
$checkout = new CheckoutController($page->getUser(), $page->getCart());

echo $page->displayPage($checkout->getView()->deliveryPage());
?>