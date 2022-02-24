<?php 
require_once("/wamp64/www/getwhisky-mvc/src/php/page.class.php");
if (isset($_POST['function'])) {
    $functionToCall = util::sanInt($_POST['function']);

    switch ($functionToCall) {
        case 1:
            loadMoreProducts();
        break;
        default:
            echo json_encode(0);
        break;
    }
}


function loadMoreProducts() 
{
    if (!util::valInt($_POST["offset"]) || !util::valStr($_POST['category'])) return;
    $categoryName = util::sanStr($_POST['category']);
    $offset = util::sanInt($_POST['offset']);

    $category = new CategoryController();

    $category->initCategoryByName($categoryName);

    if ($category->getCategoryProducts($offset)) {
        $html = $category->getView()->productsOnly();
        echo json_encode($html);
    } else {
        echo json_encode("");
    }
}
?>