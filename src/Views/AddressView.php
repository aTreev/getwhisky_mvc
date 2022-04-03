<?php
namespace Getwhisky\Views;

class AddressView
{
    private $address;

    public function __construct($address)
    {
        $this->address = $address;
    }

    public function addressItem()
    {
        $html = "";
        $html.="<div class='address-item' address-id='".$this->address->getId()."'>";


            $html.="<div class='left d-flex flex-column'>";
            
                $html.="<p class='address-name'>".$this->address->getAddressIdentifier()."</p>";

                $html.="<div class='address-details'>";
                    $html.="<p>".$this->address->getLine1()."</p>";
                    if ($this->address->getLine2()) $html.="<p><span class='separator'>,&nbsp;</span>".$this->address->getLine2()."</p>";
                    $html.="<p><span class='separator'>,&nbsp;</span>".$this->address->getCity()."</p>";
                    $html.="<p><span class='separator'>,&nbsp;</span>".$this->address->getPostcode()."</p>";
                $html.="</div>";
                $html.="<div class='address-details-2'>";
                if ($this->address->getCounty())$html.="<p>".$this->address->getCounty()."</p>";
                $html.="</div>";

            $html.="</div>";

            $html.="<div class='right d-flex'>";
                $html.="<button id='edit-btn-".$this->address->getId()."'>EDIT</button>";
                $html.="<button id='delete-btn-".$this->address->getId()."'>DELETE</button>";
            $html.="</div>";
            

        $html.="</div>";
        $html.="<div class='address-item-edit' id='edit-".$this->address->getId()."'>";
            $html.=$this->addressEditForm();
        $html.="</div>";

        return $html;
    }

    public function deliveryItemView()
    {
        $html = "";
        $html.="<div class='address-item' address-id='{$this->address->getId()}'>";
        $html.="<div class='address-identifier'>";
            $html.="<p>{$this->address->getAddressIdentifier()}</p>";
        $html.="</div>";
        $html.="<div class='address-details'>";
            $html.="<p>{$this->address->getLine1()},</p>";
            if ($this->address->getLine2()) $html.="<p>{$this->address->getLine2()},</p>";
            $html.="<p>{$this->address->getCity()},</p>";
            if ($this->address->getCounty()) $html.="<p>{$this->address->getCounty()},</p>";
            $html.="<p>{$this->address->getPostCode()}</p>";
        $html.="</div>";

        $html.="</div>";
        return $html;
    }

    public function addressEditForm()
    {
        $html = "";

        $html.="<form id='edit-form-".$this->address->getId()."'>";

            $html.="<input type='hidden' name='address-id' value='".$this->address->getId()."'/>";

            // Identifier
            $html.="<div class='mb-4'>";
                $html.="<label>Identifier: <span class='required'>*</span> (A name to identify this address) </label>";
                $html.="<input type='text' class='form-control' name='identifier' value='".$this->address->getAddressIdentifier()."' autocomplete='none'>";
            $html.="</div>";

            // Recipient
            $html.="<div class='mb-4'>";
                $html.="<label>Recipient: <span class='required'>*</span></label>";
                $html.="<input type='name' class='form-control' name='recipient' value='".$this->address->getRecipientName()."' autocomplete='none'>";
            $html.="</div>";

            // Address Line 1
            $html.="<div class='mb-4'>";
                $html.="<label>Address line 1:  <span class='required'>*</span></label>";
                $html.="<input type='street' class='form-control' name='line1' value='".$this->address->getLine1()."'>";
            $html.="</div>";

            // Address Line 2
            $html.="<div class='mb-4'>";
                $html.="<label>Address line 2:</label>";
                $html.="<input type='street' class='form-control' name='line2' value='".$this->address->getLine2()."'>";
            $html.="</div>";

            // Postcode
            $html.="<div class='mb-4'>";
                $html.="<label>Postcode: <span class='required'>*</span></label>";
                $html.="<input type='postcode' class='form-control' name='postcode' maxlength='10' value='".$this->address->getPostcode()."'>";
            $html.="</div>";

            // City
            $html.="<div class='mb-4'>";
                $html.="<label>City: <span class='required'>*</span></label>";
                $html.="<input type='city' class='form-control' name='city' value='".$this->address->getCity()."'>";
            $html.="</div>";

            // County
            $html.="<div class='mb-4'>";
                $html.="<label>County:</label>";
                $html.="<input type='county' class='form-control' name='county' value='".$this->address->getCounty()."'>";
            $html.="</div>";

            $html.="<div class='mb-4 d-flex gap-2'>";
                $html.="<button class='btn btn-success' id='update-btn-".$this->address->getId()."'>SAVE CHANGES</button>";
                $html.="<button class='btn btn-danger' id='cancel-edit-btn-".$this->address->getId()."'>CANCEL</button>";

            $html.="</div>";
        $html.="</form>";

        return $html;
    }

    public function createAddressForm()
    {
        $html = "";

        $html.="<div class='add-address-root'>";

            $html.="<div class='header'>";
                $html.="<h3>Add a new address</h3>";
            $html.="</div>";
    
            $html.="<form id='add-address-form'>";
                // Identifier
                $html.="<div class='mb-4'>";
                    $html.="<label>Identifier: <span class='required'>*</span> (A name to identify this address) </label>";
                    $html.="<input type='text' class='bs-input' name='identifier' autocomplete='none'>";
                $html.="</div>";

                // Recipient
                $html.="<div class='mb-4'>";
                    $html.="<label>Recipient: <span class='required'>*</span></label>";
                    $html.="<input type='name' class='bs-input' name='recipient' autocomplete='none'>";
                $html.="</div>";

                // Address Line 1
                $html.="<div class='mb-4'>";
                    $html.="<label>Address line 1:  <span class='required'>*</span></label>";
                    $html.="<input type='street' class='bs-input' name='line1'>";
                $html.="</div>";

                // Address Line 2
                $html.="<div class='mb-4'>";
                    $html.="<label>Address line 2:</label>";
                    $html.="<input type='street' class='bs-input' name='line2'>";
                $html.="</div>";

                // Postcode
                $html.="<div class='mb-4'>";
                    $html.="<label>Postcode: <span class='required'>*</span></label>";
                    $html.="<input type='postcode' class='bs-input' name='postcode' maxlength='10' >";
                $html.="</div>";

                // City
                $html.="<div class='mb-4'>";
                    $html.="<label>City: <span class='required'>*</span></label>";
                    $html.="<input type='city' class='bs-input' name='city'>";
                $html.="</div>";

                // County
                $html.="<div class='mb-4'>";
                    $html.="<label>County:</label>";
                    $html.="<input type='county' class='bs-input' name='county'>";
                $html.="</div>";

                $html.="<div class='mb-4 d-flex gap-2'>";
                    $html.="<input type='submit' value='SAVE' class='btn btn-success px-4'>";
                    $html.="<button class='btn btn-danger' id='cancel-add'>CANCEL</button>";
                $html.="</div>";
            $html.="</form>";

        $html.="</div>";

        return $html;
    }
}
?>