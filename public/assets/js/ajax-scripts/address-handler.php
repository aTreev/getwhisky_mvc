<?php
$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "/var/www/getwhisky-mvc";
require_once "$path/vendor/autoload.php";

use Getwhisky\Controllers\AddressController;
use Getwhisky\Controllers\Page;
use Getwhisky\Util\InputValidator;
use Getwhisky\Util\Util;

if (isset($_POST['function']) && Util::valInt($_POST['function'])) {
    $functionToCall = Util::sanInt($_POST['function']);

    switch($functionToCall) {
        case 1:
            updateAddress();
        break;
    }
}


function updateAddress()
{
    $inputValidator = new InputValidator();
    $addressid = (Util::valStr($_POST['address_id'])) ? Util::sanStr($_POST['address_id']) : "";
    $identifier = $inputValidator->inputName("identifier")->value($_POST['identifier'])->sanitize("string")->required()->maxLen(90)->getResult();
    $recipient = $inputValidator->inputName("recipient")->value($_POST['recipient'])->sanitize("string")->required()->maxLen(90)->getResult();
    $postcode = $inputValidator->inputName("postcode")->value(strtoupper($_POST['postcode']))->sanitize("string")->required()->match("postcode")->maxLen(10)->getResult();
    $mobile = $inputValidator->inputName("mobile")->value($_POST['mobile'])->sanitize("string")->match("mobile")->maxLen(12)->getResult();
    $line1 = $inputValidator->inputName("line1")->value($_POST['line1'])->sanitize("string")->maxLen(100)->required()->getResult();
    $line2 = $inputValidator->inputName("line2")->value($_POST['line2'])->sanitize("string")->maxLen(100)->getResult();
    $city = $inputValidator->inputName("city")->value($_POST['city'])->sanitize("string")->maxLen(50)->required()->getResult();
    $county = $inputValidator->inputName("county")->value($_POST['county'])->sanitize("string")->maxLen(50)->getResult();
    
    if ($inputValidator->getErrors()) {
        echo json_encode(['invalid' => $inputValidator->getErrors()]);
        return;
    }

    // find the address
    $address = new AddressController();
    $found = $address->initAddressById($addressid);
    
    // Return generic error if not found
    if (!$found) {
        echo json_encode(['success' => 0, 'message' => "Something went wrong please try again"]);
        return;
    }
    // Attempt update
    $result = $address->updateAddress($identifier, $recipient, $mobile, $postcode, $line1, $line2, $city, $county);

    if ($result) {
        $page = new Page(2, true);
        echo json_encode(['success' => 1, 'message' => "Address updated successfully", 'html' => $page->getUser()->getView()->addressPage()['html']]);
    } else {
        echo json_encode("");
    }
    
    
    return;
}
?>
