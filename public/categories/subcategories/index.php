<?php
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
$page = new Page(0);
if (isset($_GET['s']) && util::valInt($_GET['s'])) {
    $id = util::sanInt($_GET['s']);
    $subcategory = new SubcategoryController();
    $subcategory->initSubcategoryById($id);
    echo $page->displayPage($subcategory->getView()->subcategoryPageFullView());
}

?>