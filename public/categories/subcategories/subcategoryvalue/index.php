<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Controllers\SubcategoryValueController;
use Getwhisky\Util\Util;

require_once 'C:/wamp64/www/getwhisky-mvc/vendor/autoload.php';
$page = new Page(0);

// edit subcategoryvalueview to edit this page
if (isset($_GET['s']) && Util::valInt($_GET['s'])) {
    $subcategoryValue = new SubcategoryValueController();
    $exists = $subcategoryValue->initSubcategoryValue(Util::sanInt($_GET['s']));
    
    if ($exists) {
        $subcategoryValue->loadProductsByOffsetLimit(0,constant("product_retrieve_count"));
        echo $page->displayPage($subcategoryValue->getView()->subcategoryValuePageFullView());
    } else {
        echo $page->displayPage(['html' => "<p>subcategory not found!</p>"]);
    }
} else {
    echo $page->displayPage(['html' => "<p>subcategory not found!</p>"]);
}


?>