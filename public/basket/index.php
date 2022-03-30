<?php
use Getwhisky\Controllers\Page;
use Getwhisky\Controllers\CartController;
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
$page = new Page(0);
echo $page->displayPage($page->getCart()->getView()->cartPage());
?>