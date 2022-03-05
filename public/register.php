<?php

use Getwhisky\Controllers\Page;

require_once 'C:/wamp64/www/getwhisky-mvc/vendor/autoload.php';
$page = new Page(0);

if ($page->getUser()->getAccessLevel() == 2) header("Location: user/account");
if ($page->getUser()->getAccessLevel() == 3) header("Location: admin/home");

echo $page->displayPage($page->getUser()->getView()->register());
?>
