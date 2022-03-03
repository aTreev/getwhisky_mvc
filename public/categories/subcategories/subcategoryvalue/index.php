<?php
require_once("../../../../src/php/page.class.php");
$page = new Page(0);

// edit subcategoryvalueview to edit this page
$subcategoryValue = new SubcategoryValueController();
$subcategoryValue->initSubcategoryValue($_GET['s']);
$subcategoryValue->loadProductsByOffsetLimit(0,20);
echo $page->displayPage($subcategoryValue->getView()->subcategoryValuePageFullView());

?>