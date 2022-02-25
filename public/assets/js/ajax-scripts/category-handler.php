<?php 
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
if (isset($_POST['function'])) {
    $functionToCall = util::sanInt($_POST['function']);

    switch ($functionToCall) {
        case 1:
            loadMoreProducts();
        break;
        case 2:
            loadFilteredProducts();
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
function loadMoreProducts() 
{
    if (!util::valInt($_POST["offset"]) || !util::valStr($_POST['category'])) return;
    $categoryName = util::sanStr($_POST['category']);
    $offset = (int)util::sanInt($_POST['offset']);
    $limit = 20;

    $category = new CategoryController();

    $category->initCategoryByName($categoryName);

    if ($category->getProductsByOffsetLimit($offset, $limit)) {
        $html = $category->getView()->productsOnly();
        echo json_encode(['html' => $html, 'newOffset' => $offset+$limit]);
    } else {
        echo json_encode(['newOffset' => $offset+$limit]);
    }
}

function loadFilteredProducts()
{
    if (!util::valInt($_POST['offset']) || !util::valStr($_POST['category'])) return;
    $offset = (int)util::sanInt($_POST['offset']);
    $limit = 20;
    $categoryName = (string)util::sanStr($_POST['category']);
    $filters = [];
    foreach($_POST['filters'] as $filter) {
        if (util::valInt($filter)) {
            array_push($filters, (int)util::sanInt($filter));
        }
    }

    $category = new CategoryController();
    $category->initCategoryByName($categoryName);

    if ($category->getFilteredProductsByOffsetLimit($filters, $offset, $limit)) {
        $html = $category->getView()->productsOnly();
        echo json_encode(['html' => $html, 'newOffset' => $offset+$limit]);
    } else {
        echo json_encode(['newOffset' => $offset+$limit]);
    }
}
?>