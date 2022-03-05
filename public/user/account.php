<?php

use Getwhisky\Controllers\Page;

require_once 'C:/wamp64/www/getwhisky-mvc/vendor/autoload.php';
$page = new Page(2);
echo $page->displayPage($page->getUser()->getView()->index());
?>