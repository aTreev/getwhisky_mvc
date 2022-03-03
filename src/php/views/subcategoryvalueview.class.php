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

    public function backwardsNavigation()
    {
        $html = "";
        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='back-nav-content container'>";
                $html.="<a href='/'>Home</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<a href='/categories/?c=".$this->subcategoryValue->getCategoryId()."'>".ucwords($this->subcategoryValue->getCategoryName())."</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<a href='/categories/subcategories?s=".$this->subcategoryValue->getSubcategoryId()."'>".ucwords($this->subcategoryValue->getSubcategoryName())."</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<p class='current-page'>".ucwords($this->subcategoryValue->getName())."</p>";

            $html.="</div>";
        $html.="</div>";

        return $html;
    }

    public function subcategoryValuePageFullView()
    {
        $html = "";
        $title = ucwords($this->subcategoryValue->getName());
        $style = "/assets/style/category-page.css";
        $script = "/assets/js/subcategory-page.js";

        $html.=$this->backwardsNavigation();
        $html.="
        <div class='break-container hero-container'>
            <div class='hero-text'>
                <h1 class='text-white'>".ucwords($this->subcategoryValue->getName())."</h1>
                <p class='text-white-faded'>".$this->subcategoryValue->getDescription()."</p>
            </div>
            <img src='".$this->subcategoryValue->getImage()."' class='hero-image' />
        </div>";


        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
            $html.="<input type='hidden' id='subcategoryval-id' value='".$this->subcategoryValue->getId()."'>";
        foreach($this->subcategoryValue->getProducts() as $product) {
            $html.=$product->getView()->categoryPageView();
        }
        $html.="</div>";
        return ['html' => $html, 'style' => $style, 'script' => $script, 'title' => $title];
    }


    public function productsOnly()
    {
        $html = "";
        foreach($this->subcategoryValue->getProducts() as $product) {
            if ($product->isActive()) $html.=$product->getView()->categoryPageView();
        }
        return $html;
    }
}

?>