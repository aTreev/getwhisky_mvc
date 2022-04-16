<?php

use Getwhisky\Controllers\Page;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

$page = new Page(2);
echo $page->displayPage($page->getUser()->getView()->orderPage());
?>