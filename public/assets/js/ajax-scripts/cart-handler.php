<?php
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

use Getwhisky\Controllers\Page;
use Getwhisky\Util\Util;

if (isset($_POST['function']) && util::valInt($_POST['function'])) {
    $functionToCall = util::sanInt($_POST['function']);

    switch($functionToCall) {
        case 1:
            addToCart();
        break;
        case 2:
            removeFromCart();
        break;
        case 3:
            updateCartItemQuantity();
        break;
    }
}


/*********
 * Adds a product to cart using the Page's CartController Obj
 **********************************/
function addToCart()
{
    // Guard clause - return if productid not set or not int
    if (!isset($_POST['productid']) || !util::valInt($_POST['productid'])) echo json_encode(['result' => 0, 'message' => "Invalid product supplied"]);

    // Quantity = quantity set OR 1
    $quantity = (isset($_POST['quantity']) && util::valInt($_POST['quantity'])) ? util::sanInt($_POST['quantity']) : 1;
    $productid = util::sanInt($_POST['productid']);
    $page = new Page(0, true);
    $userCart = $page->getCart();
    
    echo json_encode($userCart->addToCart($productid, $quantity));
}

function removeFromCart()
{
    // Guard clause - return if productid not set or not int
    if (!isset($_POST['productid']) || !util::valInt($_POST['productid'])) echo json_encode(['result' => 0, 'message' => "Invalid product supplied"]);

    $productid = util::sanInt($_POST['productid']);

    $page = new Page(0, true);
    $userCart = $page->getCart();

    // Attempt remove from cart
    $result = $userCart->removeFromCart($productid);

    // if removed get new html from cart view
    if ($result) {
        $html = $userCart->getView()->cartPage()['html'];
        $result['html'] = $html;
    }
    echo json_encode($result);
}

function updateCartItemQuantity()
{
    // Guard clause - return if productid not set or not int
    if (!isset($_POST['productid']) || !util::valInt($_POST['productid'])) echo json_encode(['result' => 0, 'message' => "Invalid product supplied"]);
    if (!isset($_POST['quantity']) || !util::valInt($_POST['quantity'])) echo json_encode(['result' => 0, 'message' => "Please provide a valid quantity"]);

    $productid = util::sanInt($_POST['productid']);
    $quantity = util::sanInt($_POST['quantity']);
    $page = new Page(0, true);
    $userCart = $page->getCart();

    $result = $userCart->updateCartItemQuantity($productid, $quantity);
    if ($result){
        $html = $userCart->getView()->cartPage()['html'];
        $result['html'] = $html;
    }

    echo json_encode($result);
}
?>