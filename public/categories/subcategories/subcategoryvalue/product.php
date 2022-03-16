<?php
// init page
// get product by name
// display product page

// get display related products
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

use Getwhisky\Controllers\Page;
use Getwhisky\Controllers\ProductController;


$page = new Page(0);
$product = new ProductController;
$product->initProductByName(str_replace("-", " ", $_GET['p']));
echo $page->displayPage($product->getView()->productPageFullView());
?>