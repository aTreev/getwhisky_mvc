<?php 
require_once('../src/php/page.class.php');
$page = new Page(0);

if ($page->getUser()->getAccessLevel() == 2) header("Location: user/account");
if ($page->getUser()->getAccessLevel() == 3) header("Location: admin/home");

echo $page->displayPage($page->getUser()->getView()->register());
?>
