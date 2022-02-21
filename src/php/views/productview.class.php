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
        $html = "";
        $html.="<div class='product'>";
        $html.="<img src='".$this->product->getImage()."' class='img-fluid' style='max-width: 300px;'/>";
        $html.="<p>".$this->product->getName()."</p>";
        $html.="</div>";

        return $html;
    }
}
?>