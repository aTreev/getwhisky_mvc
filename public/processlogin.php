<?php
require_once("../src/php/page.class.php");
$page = new Page(0);

if (isset($_POST['email']) && util::valEmail($_POST['email']) && isset($_POST['password']) && util::valStr($_POST['password'])) {
    $email = util::sanEmail($_POST['email']);
    $password = util::sanStr($_POST['password']);
    $result=$page->login($email, $password, $autoRedirect="true");
    if (!$result['authenticated']) {
        echo "authenticaton failed"; 
    }

}
?>