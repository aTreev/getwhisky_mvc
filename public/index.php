<?php

use Getwhisky\Controllers\Page;
require_once 'C:/wamp64/www/getwhisky-mvc/vendor/autoload.php';
$page = new Page(0);
echo $page->displayPage([
    'html' => "
    <p style='margin-top:20px;'>Hello! This site is a work in progress and is the second iteration of my ecommerce site.<br>This site uses an MVC design pattern.</p>
    <p>Features that have been implemented in this iteration so far:</p>
    <ul style='margin-top:-10px;'>
        <li>Authentication - reg / login</li>
        <li>Product categories, subcategories</li>
        <li>Product pages</li>
        <li>Cart features - add/update/remove</li>
    </ul>
    <p style='margin-top:10px;'>Currently the cart doesn't function without an authenticated user - You may sign in using the following credentials:</p>
    <form method='post' action='processlogin.php'>
        <input type='hidden' id='email' name='email' value='johndoe@example.com'>
        <input type='hidden' id='password' name='password' value='Pa££w0rd'>
        <button type='submit' class='btn btn-success'>Click here to log in</button>
    </form>
    <p style='margin-top:20px;font-weight:500;'>After logging in please see the whisky category for an example of a working category</p>

    <p>The github repo for this site can be seen <a href='https://github.com/aTreev/getwhisky_mvc'>here</a>
    "
]);

?>