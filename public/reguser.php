<?php 
    require_once("../src/php/page.class.php");
    $page = new Page(0);
    if (util::valEmail($_POST['email']) && util::valStr($_POST['password']) && util::valStr($_POST['first-name']) && util::valStr($_POST['surname']) && util::valStr($_POST['dob'])) {
        $email = util::sanEmail($_POST['email']);
        $password = util::sanStr($_POST['password']);
        $firstName = util::sanStr($_POST['first-name']);
        $surname = util::sanStr($_POST['surname']);
        $dob = util::sanStr($_POST['dob']);

        try {
            $result = $page->getUser()->regUser($email, $password, $firstName, $surname, $dob);
            $page->login($email, $password);
            echo var_dump($result);
            if (!$result) {
                echo "Something went wrong";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
?>