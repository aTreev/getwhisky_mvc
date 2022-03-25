<?php
namespace Getwhisky\Controllers;

use Getwhisky\Model\AddressModel;
use Getwhisky\Views\AddressView;

class AddressController extends AddressModel
{
    private string $id;
    private string $userid;
    private string $addressIdentifier;
    private string $recipientName;
    private ?string $mobileNumber;
    private string $postcode;
    private string $line1;
    private ?string $line2;
    private string $city;
    private ?string $county;
    private AddressView $addressView;

    public function getId() { return $this->id; }    
    public function getUserid() { return $this->userid; }    
    public function getAddressIdentifier() { return $this->addressIdentifier; }
    public function getRecipientName() { return $this->recipientName; }
    public function getMobileNumber() { return $this->mobileNumber; }
    public function getPostcode() { return $this->postcode; }
    public function getLine1() { return $this->line1; }
    public function getLine2() { return $this->line2; }
    public function getCity() { return $this->city; }
    public function getCounty() { return $this->county; }

    public function getView() { return $this->addressView = new AddressView($this); }


    private function setId($id) { $this->id = $id; return $this;}
    private function setUserid($userid) { $this->userid = $userid; return $this; }
    private function setAddressIdentifier($addressIdentifier) { $this->addressIdentifier = $addressIdentifier; return $this; }
    private function setRecipientName($recipientName) { $this->recipientName = $recipientName; return $this; }
    private function setMobileNumber($mobileNumber) { $this->mobileNumber = $mobileNumber; return $this; }
    private function setPostcode($postcode) { $this->postcode = $postcode; return $this; }
    private function setLine1($line1) { $this->line1 = $line1; return $this; }
    private function setLine2($line2) { $this->line2 = $line2; return $this; }
    private function setCity($city) { $this->city = $city; return $this; }
    private function setCounty($county) { $this->county = $county; return $this; }

    public function construct()
    {
        //
    }

    // Returns the ids of the user's addresses
    public function getUserAddressIds($userid) 
    {
        return parent::getUserAddressIdsModel($userid);
    }

    public function getAddressIds()
    {
        return parent::getAddressIdsModel();
    }


    /********
     * Initialize the address from the passed in address_id and user_id
     * Called from via the UserController class
     *******************************/
    public function initAddressById($id, $userid)
    {
        $addressFound = false;
        $addressData = parent::getUserAddressById($id, $userid);
        if ($addressData) {
            $data = $addressData[0];
            $addressFound = true;

            $this->setId($data['id'])
            ->setUserid($data['user_id'])
            ->setAddressIdentifier($data['address_name'])
            ->setRecipientName($data['recipient'])
            ->setMobileNumber($data['mobile_number'])
            ->setPostcode($data['postcode'])
            ->setLine1($data['line1'])
            ->setLine2($data['line2'])
            ->setCity($data['city'])
            ->setCounty($data['county']);
        }

        return $addressFound;
    }


    public function updateAddress($addressIdentifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county)
    {
        $result = parent::updateAddressModel($this->getId(), $this->getUserid(), $addressIdentifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county);
        return $result;
    }

    public function deleteAddress()
    {
        $result = parent::deleteAddressModel($this->getId(), $this->getUserid());
        return $result;
    }

    public function addAddress($id, $userid, $identifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county)
    {
        $result = parent::addAddressModel($id, $userid, $identifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county);
        return $result;
    }
}
?>