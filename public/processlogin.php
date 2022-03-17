<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
$page = new Page(0);

if (isset($_POST['email']) && Util::valEmail($_POST['email']) && isset($_POST['password']) && Util::valStr($_POST['password'])) {
    $email = Util::sanEmail($_POST['email']);
    $password = Util::sanStr($_POST['password']);
    $result=$page->login($email, $password, $autoRedirect="true");
    if (!$result['authenticated']) {
        echo "authenticaton failed"; 
    }

}
?>