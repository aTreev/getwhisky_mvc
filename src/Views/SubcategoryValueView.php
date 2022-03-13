<?php
namespace Getwhisky\Views;

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


    public function productCountAndShowFiltersBar()
    {
        return "<div class='break-container p-3 bg-white shadow-sm' id='filter-bar'>
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

        $html.=SharedView::backwardsNavigation(array(
            ['url' => "/categories/?c=".$this->subcategoryValue->getCategoryId(), 'pageName' => ucwords($this->subcategoryValue->getCategoryName())],
            ['url' => "/categories/subcategories?s=".$this->subcategoryValue->getSubcategoryId(), 'pageName' => ucwords($this->subcategoryValue->getSubcategoryName())],
            ['url' => '', 'pageName' => ucwords($this->subcategoryValue->getName())]
        ));

        $html.=SharedView::bannerImage([
            'header' => ucwords($this->subcategoryValue->getName()), 
            'text' => $this->subcategoryValue->getDescription(), 
            'image' => $this->subcategoryValue->getImage()
        ]);

        // Filter show bar
        $html.=$this->productCountAndShowFiltersBar();

        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
            $html.="<input type='hidden' id='subcategory-id' value='".$this->subcategoryValue->getId()."'>";
            $html.=$this->products();
        $html.="</div>";

        $html.="
        <div id='filter-root' class='product-filters'>
            <div class='bg-light p-4 text-center d-flex align-items-center'>
                <i class='fa-solid fa-xmark me-5 site-icon-black' id='close-filters'></i>
                <h4 class='mb-0'>Product Filters</h4>
            </div>
            <div class='product-sort'>
                <div class='sorting-header'>
                    <p class='mb-1'>Sort by</p>
                </div>
                <div class='sorting-options'>
                    <button name='sort-option' class='btn btn-danger' id=''>Latest</button>
                    <button name='sort-option' class='btn btn-danger' id='asc'>price (low)</button>
                    <button name='sort-option' class='btn btn-danger' id='desc'>price (high)</button>
                </div>
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