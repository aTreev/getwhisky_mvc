<?php
class SubcategoryValueView
{
    private $subcategoryValue;

    public function __construct($subcategoryValue)
    {
        $this->setSubcategoryValue($subcategoryValue);    
    }


    private function setSubcategoryValue($subcategoryValue) { $this->subcategoryValue = $subcategoryValue; }


    public function menu() {
        if ($this->subcategoryValue->getProductCount() == 0) return "";
        return "<a href='/categories/subcategories/subcategoryvalue?s=".$this->subcategoryValue->getId()."'>".$this->subcategoryValue->getName()."</a>";

    }

    public function subcategoryValuePageFullView()
    {
        $html = "";
        $title = ucwords($this->subcategoryValue->getName());
        $style = "/assets/style/category-page.css";

        $html.="
        <div class='break-container' style='background: rgba(0, 0, 0, 0.8)!important;'>
            <div class='hero-text'>
                <h1 class='text-white'>".ucwords($this->subcategoryValue->getName())."</h1>
                <p class='text-white-faded'>".$this->subcategoryValue->getDescription()."</p>
            </div>
            <img src='".$this->subcategoryValue->getImage()."' class='hero-image' />
        </div>";
        
        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='container'>";
                $html.="<a href='/categories/subcategories?s=".$this->subcategoryValue->getSubcategoryId()."'>Back to subcategory</a>";
            $html.="</div>";
        $html.="</div>";

        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
        foreach($this->subcategoryValue->getProducts() as $product) {
            $html.=$product->getView()->categoryPageView();
        }
        $html.="</div>";
        return ['html' => $html, 'style' => $style, 'script' => '', 'title' => $title];
    }
}

?>