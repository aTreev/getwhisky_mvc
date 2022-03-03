<?php 
require_once('../../src/php/page.class.php');
$page = new Page(0);

/*****************
 * 
 */
if (isset($_GET['c']) && util::valInt($_GET['c'])) {
    $categoryId = util::sanInt(str_replace("-", " ", $_GET['c']));

    // Get current category
    $category = new CategoryController();
    $exists = $category->initCategory($categoryId);

    // Check if it exists
    if ($exists) {
        // load first 20 products
        $category->getProductsByOffsetLimit($offset=0, $limit=5);
        // call the page's view and pass the category's view
        echo $page->displayPage($category->getView()->categoryPageFullView()); 
    } else {
        echo $page->displayPage(['html' =>"<h1>Category not found</h1>"]);
    }
} else {
    echo $page->displayPage(['html' =>"<h1>Category not found</h1>"]);
}
?>
