<?php 
require_once('../src/php/page.class.php');
$page = new Page(0);
echo $page->displayPage($page->getUser()->getView()->register());
?>
