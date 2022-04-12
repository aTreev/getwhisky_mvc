<?php
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

use Getwhisky\Controllers\AddressController;
use Getwhisky\Controllers\CheckoutController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\InputValidator;
use Getwhisky\Util\UniqueIdGenerator;
use Getwhisky\Util\Util;

if (isset($_POST['function']) && Util::valInt($_POST['function'])) {
    $functionToCall = Util::sanInt($_POST['function']);

    switch($functionToCall) {
        case 1: updateAddress(); break;
        case 2: deleteAddress(); break;
        case 3: addAddress(); break;
    }
}


function updateAddress()
{
    $inputValidator = new InputValidator();
    $addressid = (Util::valStr($_POST['address_id'])) ? Util::sanStr($_POST['address_id']) : "";
    $identifier = $inputValidator->inputName("identifier")->value($_POST['identifier'])->sanitize("string")->required()->maxLen(90)->getResult();
    $recipient = $inputValidator->inputName("recipient")->value($_POST['recipient'])->sanitize("string")->required()->maxLen(90)->getResult();
    $postcode = $inputValidator->inputName("postcode")->value(strtoupper($_POST['postcode']))->sanitize("string")->required()->match("postcode")->maxLen(10)->getResult();
    $line1 = $inputValidator->inputName("line1")->value($_POST['line1'])->sanitize("string")->maxLen(100)->required()->getResult();
    $line2 = $inputValidator->inputName("line2")->value($_POST['line2'])->sanitize("string")->maxLen(100)->getResult();
    $city = $inputValidator->inputName("city")->value($_POST['city'])->sanitize("string")->maxLen(50)->required()->getResult();
    $county = $inputValidator->inputName("county")->value($_POST['county'])->sanitize("string")->maxLen(50)->getResult();
    
    if ($inputValidator->getErrors()) {
        echo json_encode(['invalid' => $inputValidator->getErrors()]);
        return;
    }
    
    // Check if user address exists
    $address = new AddressController();
    $page = new Page(2, true);
    $found = $address->initAddressById($addressid, $page->getUser()->getId());
    
    // Return generic error if not found
    if (!$found) {
        echo json_encode(['success' => 0, 'message' => "Something went wrong please try again"]);
        return;
    }
    // Attempt update
    $result = $address->updateAddress($identifier, $recipient, $postcode, $line1, $line2, $city, $county);

    if ($result) {
        echo json_encode(['success' => 1, 'message' => "Address updated successfully", 'html' => (new Page(2, true))->getUser()->getView()->addressPage()['html']]);
    } else {
        echo json_encode('');
    }
}

function deleteAddress()
{
    $addressid = (Util::valStr($_POST['address_id'])) ? Util::sanStr($_POST['address_id']) : "";

    // Check if user address exists
    $page = new Page(2, true);
    $address = new AddressController();
    $found = $address->initAddressById($addressid, $page->getUser()->getId());

    if (!$found) {
        echo json_encode(['success' => 0, 'message' => "Something went wrong please try again"]);
        return;
    }

    $result = $address->deleteAddress();
    if ($result) {
        echo json_encode(['success' => 1, 'html' => (new Page(2, true))->getUser()->getView()->addressPage()['html']]);
    } else {
        echo json_encode("");
    }
}

function addAddress()
{
    $inputValidator = new InputValidator();

    $identifier = $inputValidator->inputName("identifier")->value($_POST['identifier'])->sanitize("string")->required()->maxLen(90)->getResult();
    $recipient = $inputValidator->inputName("recipient")->value($_POST['recipient'])->sanitize("string")->required()->maxLen(90)->getResult();
    $postcode = $inputValidator->inputName("postcode")->value(strtoupper($_POST['postcode']))->sanitize("string")->required()->match("postcode")->maxLen(10)->getResult();
    $line1 = $inputValidator->inputName("line1")->value($_POST['line1'])->sanitize("string")->maxLen(100)->required()->getResult();
    $line2 = $inputValidator->inputName("line2")->value($_POST['line2'])->sanitize("string")->maxLen(100)->getResult();
    $city = $inputValidator->inputName("city")->value($_POST['city'])->sanitize("string")->maxLen(50)->required()->getResult();
    $county = $inputValidator->inputName("county")->value($_POST['county'])->sanitize("string")->maxLen(50)->getResult();

    if ($inputValidator->getErrors()) {
        echo json_encode(['invalid' => $inputValidator->getErrors()]);
        return;
    }

    $contr = new AddressController();
    $page = new Page(2, true);
    $addressid = (new UniqueIdGenerator())->properties($contr->getAddressIds())->getUniqueId();

    $result = $contr->addAddress($addressid, $page->getUser()->getId(), $identifier, $recipient, $postcode, $line1, $line2, $city, $county);

    if ($result) {
        // Reload page variable and get view
        $page = new Page(2, true);
        if (util::sanStr($_POST['page'])  == "user") $view = $page->getUser()->getView()->addressPage()['html'];
        if (util::sanStr($_POST['page'] ) == "cart") $view = (new CheckoutController($page->getUser(), $page->getCart()))->getView()->deliveryPage()['html'];

        echo json_encode(['success' => 1, 'message' => "Address $identifier created", 'html' => $view]);
    } else {
        echo json_encode(['success' => 0, 'message' => "An error occurred, please try again"]);
    }
}
?>
