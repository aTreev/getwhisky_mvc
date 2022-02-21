<?php 
require_once('../../src/php/page.class.php');
$page = new Page(0);
if (isset($_GET['c'])) {
    $category = util::sanStr(str_replace("_", " ", $_GET['c']));
    echo $page->displayPage($page->generateCategoryView($category));

}
?>
