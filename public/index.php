<?php

use Getwhisky\Controllers\Page;
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";
$page = new Page(0);
echo $page->displayPage([
    'html' => "
    <p style='margin-top:20px;'>
        Hello! This site is a work in progress and is the second iteration of my ecommerce site.
        <br>This site uses an MVC design pattern.
    </p>

    <p>The github repo for this site can be seen <a href='https://github.com/aTreev/getwhisky_mvc'>here</a>

    <p style='font-weight:500;'>Features that have been implemented in this iteration so far:</p>
    <ul style='margin-top:-10px;'>
        <li>Authentication - reg / login</li>
        <li>Product categories, subcategories</li>
        <li>Product pages</li>
        <li>Cart system - add/update/remove</li>
        <li>User address system</li>
        <li>Cart notifications</li>
    </ul>
    <p style='font-weight:500;'>Features yet to be implemented</p>
    <ul style='margin-top:-10px;'>
        <li>Stripe checkout</li>
        <li>User orders</li>
        <li>PDF invoice generation</li>
        <li>Featured / recommended products</li>
        <li>Admin backend</li>
        <ul>
            <li>Product management</li>
            <li>Order management</li>
            <li>Emailing system</li>
            <li>Category / subcategory management</li>
            <li>Homepage edit/CMS</li>
        </ul>
        <li>User detail edit</li>
        <li>Auto postcode lookup</li>
        <li>Product review system</li>
        
    </ul>
    <p style='margin-top:10px;'>You may test the authentication by logging in with the following button:</p>
    <form method='post' action='processlogin.php'>
        <input type='hidden' id='email' name='email' value='johndoe@example.com'>
        <input type='hidden' id='password' name='password' value='Pa££w0rd'>
        <button type='submit' class='btn btn-success'>Click here to log in</button>
    </form>
    <p style='margin-top:20px;font-weight:500;'>After logging in please see the whisky category for an example of a working category</p>
    "
]);

?>