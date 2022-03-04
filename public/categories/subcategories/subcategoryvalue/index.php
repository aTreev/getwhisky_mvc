<?php
require_once("../../../../src/php/page.class.php");
$page = new Page(0);

// edit subcategoryvalueview to edit this page
if (isset($_GET['s']) && util::valInt($_GET['s'])) {
    $subcategoryValue = new SubcategoryValueController();
    $exists = $subcategoryValue->initSubcategoryValue(util::sanInt($_GET['s']));
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