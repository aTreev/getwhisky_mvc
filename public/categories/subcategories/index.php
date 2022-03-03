<?php
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
$page = new Page(0);
if (isset($_GET['s']) && util::valInt($_GET['s'])) {
    $id = util::sanInt($_GET['s']);
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