<?php 
require_once('../../src/php/page.class.php');
$page = new Page(0);
if (isset($_GET['c'])) {
    $category = util::sanStr(str_replace("_", " ", $_GET['c']));

    // Get current category
    $page->getCurrentCategoryByName($category);

    if ($page->getCategory()) {
       // load first 20 products
        $page->getCategory()->getCategoryProducts($offset=0, $limit=20);
        //$page->getCategory()->getCategoryProducts(2, 4);
        echo $page->displayPage($page->getCategory()->getView()->categoryPage(), '/assets/js/category-page.js'); 
    } else {
        echo $page->displayPage("<h1>Category not found</h1>");
    }
    

}
?>
