<?php 
require_once('../../src/php/page.class.php');
$page = new Page(0);
if (isset($_GET['c'])) {
    $categoryName = util::sanStr(str_replace("-", " ", $_GET['c']));

    // Get current category
    $category = new CategoryController();
    $exists = $category->initCategoryByName($categoryName);

    // Check if it exists
    if ($exists) {
       // load first 20 products
        $category->getProductsByOffsetLimit($offset=0, $limit=5);
        
        // call the page's view and pass the category's view
        echo $page->displayPage($category->getView()->categoryPageFullView()); 
    } else {
        echo $page->displayPage("<h1>Category not found</h1>");
    }
    

}
?>
