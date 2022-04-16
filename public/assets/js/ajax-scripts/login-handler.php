<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";


if (isset($_POST['email']) && Util::valEmail($_POST['email']) && isset($_POST['password']) && Util::valStr($_POST['password'])) {
    $page = new Page(0, true);
    $email = Util::sanEmail($_POST['email']);
    $password = Util::sanStr($_POST['password']);
    $message = "";

    // Attempt login
    $result=$page->login($email, $password, util::sanStr($_POST['origin']));

    // Auth fail send message
    if (!$result['authenticated']) $message = "Invalid email or password";
    

    echo json_encode([
        'authenticated' => $result['authenticated'],
        'redirectLocation' => $result['redirectLocation'],
        'message' => $message
    ]);
    

}
?>