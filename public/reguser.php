<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

require_once 'C:/wamp64/www/getwhisky-mvc/vendor/autoload.php';
$page = new Page(0);
    if (Util::valEmail($_POST['email']) && Util::valStr($_POST['password']) && Util::valStr($_POST['first-name']) && Util::valStr($_POST['surname']) && Util::valStr($_POST['dob'])) {
        $email = Util::sanEmail($_POST['email']);
        $password = Util::sanStr($_POST['password']);
        $firstName = Util::sanStr($_POST['first-name']);
        $surname = Util::sanStr($_POST['surname']);
        $dob = Util::sanStr($_POST['dob']);

        try {
            $result = $page->getUser()->regUser($email, $password, $firstName, $surname, $dob);
            
            if ($result) {
                $page->login($email, $password, true);
            }
            
            if (!$result) {
                echo "Something went wrong";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
?>