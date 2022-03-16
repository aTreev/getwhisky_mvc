<?php
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "var/www/getwhisky-mvc";
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

    $result = $userCart->removeFromCart($productid);

    // if removed get new html from cart view
    if ($result) {
        $html = $userCart->getView()->index()['html'];
        $result['html'] = $html;
    }
    echo json_encode($result);
}
?>