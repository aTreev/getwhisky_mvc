<?php 
require_once("../src/php/page.class.php");
$page = new Page(2);
echo $page->displayPage($page->getUser()->getView()->index());
?>