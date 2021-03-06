<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Controllers\SubcategoryController;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
$page = new Page(0);
if (isset($_GET['s']) && Util::valInt($_GET['s'])) {
    $id = Util::sanInt($_GET['s']);
    $subcategory = new SubcategoryController();
    $exists = $subcategory->initSubcategoryById($id);
    if ($exists) {
        echo $page->displayPage($subcategory->getView()->subcategoryPageFullView());
    } else {
        echo $page->displayPage(['html' => '<h1>Subcategory not found</h1>']);
    }
} else {
    echo $page->displayPage(['html' => '<h1>Subcategory not found</h1>']);
}

?>