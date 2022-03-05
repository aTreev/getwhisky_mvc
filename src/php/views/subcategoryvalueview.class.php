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

    public function bannerImage()
    {
        return "<div class='break-container hero-container'>
                    <div class='hero-text'>
                        <h1 class='text-white'>".ucwords($this->subcategoryValue->getName())."</h1>
                        <p class='text-white-faded'>".$this->subcategoryValue->getDescription()."</p>
                    </div>
                    <img src='".$this->subcategoryValue->getImage()."' class='hero-image' />
                </div>";
    }

    public function productCountAndShowFiltersBar()
    {
        return "<div class='break-container p-3 bg-white shadow-sm'>
                    <div class='container px-2 m-auto d-flex align-items-center justify-content-between gap-1 flex-wrap'>
                    <div>Showing <span id='product-count'>".count($this->subcategoryValue->getProducts())."</span> of ".$this->subcategoryValue->getProductCount()." products</div>
                    <button class='btn btn-danger' id='open-filters'><i class='fa-solid fa-filter'></i> Product Filters</button>
                    </div>
                </div>";
    }

    public function subcategoryValuePageFullView()
    {
        $html = "";
        $title = ucwords($this->subcategoryValue->getName());
        $style = "/assets/style/category-pages.css";
        $script = "/assets/js/category-pages.js";

        $html.=$this->backwardsNavigation();

        $html.=$this->bannerImage();

        // Filter show bar
        $html.=$this->productCountAndShowFiltersBar();

        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
            $html.="<input type='hidden' id='subcategory-id' value='".$this->subcategoryValue->getId()."'>";
            $html.=$this->products();
        $html.="</div>";

        $html.="
        <div id='filter-root' class='product-filters'>
            <div class='bg-light p-3 text-center d-flex align-items-center'>
                <i class='fa-solid fa-xmark me-5 site-icon-black' id='close-filters'></i>
                <h4 style='font-weight:300'>Product Filters</h4>
            </div>
            <div>
                <button name='sort-option' id=''>Latest</button>
                <button name='sort-option' id='asc'>price (low)</button>
                <button name='sort-option' id='desc'>price (high)</button>
            </div>
        </div>
        ";

        return ['html' => $html, 'style' => $style, 'script' => $script, 'title' => $title];
    }


    public function products()
    {
        $html = "";
        foreach($this->subcategoryValue->getProducts() as $product) {
            if ($product->isActive()) $html.=$product->getView()->categoryPageView();
        }
        return $html;
    }
}

?>