<?php
use Getwhisky\Controllers\Page;
use Getwhisky\Controllers\CartController;
require_once("/wamp64/www/getwhisky-mvc/vendor/autoload.php");
$page = new Page(0);
echo $page->displayPage($page->getCart()->getView()->index());
?>