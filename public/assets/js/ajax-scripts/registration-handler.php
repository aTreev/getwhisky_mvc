<?php

use Getwhisky\Controllers\Page;
use Getwhisky\Util\InputValidator;
use Getwhisky\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";


// Validate inputs
$validator = new InputValidator();

$email = $validator->inputName("email")->value($_POST['email'])->sanitize("email")->required()->maxLen(80)->getResult();
$firstName = $validator->inputName("firstname")->value($_POST['firstname'])->sanitize("string")->required()->maxLen(40)->getResult();
$surname = $validator->inputName("surname")->value($_POST['firstname'])->sanitize("string")->required()->maxLen(40)->getResult();
$dob = $validator->inputName("dob")->value($_POST['dob'])->sanitize("string")->match("date")->maxLen(10)->getResult();
$password = $validator->inputName("password")->value($_POST['password'])->sanitize("string")->maxLen(72)->getResult();
$repeatPassword = $validator->inputName("repeat_password")->value($_POST['repeat_password'])->sanitize("string")->maxLen(72)->getResult();

if ($validator->getErrors()) {
    echo json_encode(['invalid' => $validator->getErrors()]);
    return;
}

// Registration specific checks
if (!Util::verifyAge($dob, 18)) {
    echo json_encode(['invalid' => [["input" => "dob", "message" => "Must be 18 to register"]]]);
    return;
}

if ($password != $repeatPassword) {
    echo json_encode(['invalid' => [["input" => "repeat_password", "message" => "Passwords must match"]]]);
    return;
}

if (strlen($password) < 8) {
    echo json_encode(['invalid' => [["input" => "password", "message" => "Passwords must meet minimum complexity standards"]]]);
    return;
}

// Instantiate page and register user
$page = new Page(0, true);
$result = $page->getUser()->regUser($email, $password, $firstName, $surname, $dob);

// return errors if registration failed
if (!$result['insert']) {
    echo json_encode(['error' => $result['messages']]);
    return;
}
// Login
$page->login($email, $password);

// Get registration origin and send redirect url for correct location
$origin = Util::sanStr($_POST['origin']);
$url = ($origin == "checkout") ? "/checkout/" : "/user/account/";
echo json_encode(['success' => 1, 'redirectLocation' => $url]);
?>