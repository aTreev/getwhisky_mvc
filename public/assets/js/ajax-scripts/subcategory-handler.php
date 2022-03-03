<?php
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");

if (isset($_POST['function']) && util::valInt($_POST['function'])) {
    $functionToCall = util::sanInt($_POST['function']);
    switch($functionToCall) {
        case 1: loadMoreProducts(); break;
    }
} else {
    echo json_encode("0");
}




/*******
 * Loads more products from subcategoryvaluecontroller using offset/limit
 * returns html of the retrieved products
 * returns an updated offset, incremented by the limit
 * returns an end flag if there are no more products to load
 */
function loadMoreProducts()
{
    if (util::valInt($_POST['offset']) && util::valInt($_POST['subcatvalueid']))
    $subcatvalueid = (int)util::sanInt($_POST['subcatvalueid']);
    $offset = (int)util::sanInt($_POST['offset']);
    $limit = 20;
    $end = false;

    $subcategoryValueController = new SubcategoryValueController();
    $subcategoryValueController->initSubcategoryValue($subcatvalueid);
    $subcategoryValueController->loadProductsByOffsetLimit($offset, $limit);

    if (!$subcategoryValueController->getProducts()) $end = true;
    $html = $subcategoryValueController->getView()->productsOnly();

    echo json_encode(['html' => $html, 'offset' => $offset+$limit, 'end_of_products' => $end]);
}
?>