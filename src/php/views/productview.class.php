<?php
class ProductView
{
    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function categoryPageView()
    {
        $html = "
        <div class='product-c bg-white text-center'>
            <img src='".$this->product->getImage()."' class='product-c-image img-fluid'/>
            <p class='product-c-name'>".$this->product->getName()."</p>
            <p class='text-uppercase text-muted'>".$this->product->getType()."</p>
            ";
        if ($this->product->getAlcoholVolume()) {
            $html.="<p class='text-muted'>".$this->product->getBottleSize()." / ".$this->product->getAlcoholVolume()."</p>";
        }
        $html.="
            <p class='product-c-price'>£".$this->product->getPrice()."</p>
            <a href='/products/product?p=".str_replace(" ", "_", $this->product->getName())."' class='wrapper-link'><span></span></a>
        </div>
        ";

        return $html;
    }
}
?>