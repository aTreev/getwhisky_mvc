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
        $html.="<div class='address-item px-4'>";
            $html.="<p class='fw-bold text-uppercase'>".$this->address->getAddressIdentifier()."</p>";
            $html.="<div class='address-line d-flex'>";
                $html.="<p>".$this->address->getLine1()."</p>";
                if ($this->address->getLine2()) $html.="<p>, ".$this->address->getLine2()."</p>";
                $html.="<p>, ".$this->address->getPostcode()."</p>";
                $html.="<p>, ".$this->address->getCity()."</p>";
                if ($this->address->getCounty())$html.="<p>, ".$this->address->getPostcode()."</p>";
            $html.="</div>";
        $html.="</div>";

        return $html;
    }

}
?>