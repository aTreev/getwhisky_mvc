<?php

use Getwhisky\Controllers\CategoryController;
use Getwhisky\Controllers\SubcategoryValueController;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

if (isset($_POST['function'])) {
    $functionToCall = Util::sanInt($_POST['function']);

    switch ($functionToCall) {
        case 1:
            loadProductsCategory();
        break;
        case 2:
            loadProductsSubcategory();
        break;
        default:
            echo json_encode(0);
        break;
    }
}


/******
 * Initializes a category and retrieves the products
 * between the offset and limit
 *  offset increments by limit on each successful product retrieval
 */
function loadProductsCategory() 
{
    if (!Util::valInt($_POST["offset"]) || !Util::valStr($_POST['catOrSubcatId'])) return;
    $categoryid = Util::sanStr($_POST['catOrSubcatId']);
    $offset = (int)Util::sanInt($_POST['offset']);
    $sortOption = Util::sanStr($_POST['sortOption']);
    $limit = 20;
    $end = false;

    $category = new CategoryController();

    $category->initCategory($categoryid);

    if (!$category->getProductsByOffsetLimit($offset, $limit, $sortOption)) $end = true;
        
    $html = $category->getView()->products();
    echo json_encode(['html' => $html, 'newOffset' => $offset+$limit, 'end_of_products' => $end]);
}


/*******
 * Loads more products from subcategoryvaluecontroller using offset/limit
 * returns html of the retrieved products
 * returns an updated offset, incremented by the limit
 * returns an end flag if there are no more products to load
 */
function loadProductsSubcategory()
{
    if (Util::valInt($_POST['offset']) && Util::valInt($_POST['catOrSubcatId']))
    $subcatvalueid = (int)Util::sanInt($_POST['catOrSubcatId']);
    $offset = (int)Util::sanInt($_POST['offset']);
    $sortOption = Util::sanStr($_POST['sortOption']);
    $limit = 20;
    $end = false;

    $subcategoryValueController = new SubcategoryValueController();
    $subcategoryValueController->initSubcategoryValue($subcatvalueid);
    $subcategoryValueController->loadProductsByOffsetLimit($offset, $limit, $sortOption);

    if (!$subcategoryValueController->getProducts()) $end = true;
    $html = $subcategoryValueController->getView()->products();

    echo json_encode(['html' => $html, 'newOffset' => $offset+$limit, 'end_of_products' => $end]);
}

?>