<?php

use Getwhisky\Controllers\CategoryController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
$page = new Page(0);

/*****************
 * 
 */
if (isset($_GET['c']) && Util::valInt($_GET['c'])) {
    $categoryId = Util::sanInt(str_replace("-", " ", $_GET['c']));

    // Get current category
    $category = new CategoryController();
    $exists = $category->initCategory($categoryId);

    // Check if it exists
    if ($exists) {
        // load first 20 products
        $category->getProductsByOffsetLimit($offset=0, $limit=20);
        // call the page's view and pass the category's view
        echo $page->displayPage($category->getView()->categoryPageFullView()); 
    } else {
        echo $page->displayPage(['html' =>"<h1>Category not found</h1>"]);
    }
} else {
    echo $page->displayPage(['html' =>"<h1>Category not found</h1>"]);
}
?>
