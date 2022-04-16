<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";


if (isset($_POST['email']) && Util::valEmail($_POST['email']) && isset($_POST['password']) && Util::valStr($_POST['password'])) {
    $page = new Page(0, true);
    $email = Util::sanEmail($_POST['email']);
    $password = Util::sanStr($_POST['password']);

    // Attempt login
    $result=$page->login($email, $password);

    // Auth fail send message
    if (!$result['authenticated']) {
        echo json_encode(['authenticated' => 0,'message' => "Invalid email or password"]); 
        return;
    } 

    // Login success check origin and send correct redirect URL
    $origin = util::sanStr($_POST['origin']);
    $url = ($origin == "checkout") ? "/checkout/" : "/user/account/";

    echo json_encode(['authenticated' => 1,'redirectLocation' => $url]);
    

}
?>