<?php
// init page
// get product by name
// display product page

// get display related products
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
$page = new Page(0);
$product = new ProductController();
$product->initProductByName(str_replace("-", " ", $_GET['p']));
echo $page->displayPage($product->getView()->productPageFullView());
?>