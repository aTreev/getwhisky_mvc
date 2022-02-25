<?php
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
$category = new CategoryController();
$category->initCategoryByName("Whisky");
$filters = [4,5];
$offset = 0;
$limit = 20;
$category->getFilteredProductsByOffsetLimit($filters, $offset, $limit=20);
$html = $category->getView()->productsOnly();
echo $html;
?>