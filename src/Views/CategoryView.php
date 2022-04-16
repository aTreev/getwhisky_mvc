<?php
namespace Getwhisky\Views;

class CategoryView
{
    private $category;

    public function __construct($category)
    {
        $this->setCategory($category);
    }

    private function setCategory($category) { $this->category = $category; }


    public function menu()
    {
        $html = "";
        $html.="<div class='product-menu-item'>";
            $html.="<a href='/products/categories/?c=".str_replace(" ", "-", $this->category->getId())."' categoryid='".$this->category->getId()."'>".$this->category->getName()."</a>";

            $html.="<div class='subcategory-menus container-xxl'>";
            foreach($this->category->getSubcategories() as $subcategory) {
                $html.= $subcategory->getView()->menu();
                $html.="</div>";
                //foreach subcatvalue
                //display name if product count > 0
            }
            $html.="</div>";
        $html.="</div>";
        
        return $html;
    }

   
    public function productCountAndShowFilterBar()
    {
        $html = "";
        $html.="<div class='break-container p-3 bg-white shadow-sm' id='filter-bar'>";
            $html.="<div class='container px-2 m-auto d-flex align-items-center justify-content-between gap-1 flex-wrap'>";
            $html.="<div>Showing <span id='product-count'>".count($this->category->getProducts())."</span> of ".$this->category->getProductCount()." products</div>";
            $html.="<button class='btn btn-danger' id='open-filters'><i class='fa-solid fa-filter'></i> Product Filters</button>";
            $html.="</div>";
        $html.="</div>";
        return $html;
        
    }
    public function categoryPageFullView()
    {
        $script = "/assets/js/category-pages.js";
        $style = "/assets/style/category-pages.css";
        $title = ucwords($this->category->getName());
        $filterView = $this->filterView();

        $html = "";

        $html.=SharedView::backwardsNavigation(array(
            ['url' => '', 'pageName' => ucwords($this->category->getName())]
        ));

        $html.=SharedView::bannerImage([
            'header' => ucwords($this->category->getName()), 
            'text' => $this->category->getDescription(), 
            'image' => $this->category->getImage()
        ]);

        $html.=$this->productCountAndShowFilterBar();

        // Filters
        $html.="<input type='hidden' id='category-id' value='".$this->category->getId()."'>";
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
                <div class='filter-items'>
                    $filterView
                </div>
                
            </div>
        ";

        // Products
        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
            $html.=$this->products();
        $html.="</div>";

        return ['html' => $html, 'script' => $script, 'style' => $style, 'title' => $title];
    }


    public function products()
    {
        $html = "";
        foreach($this->category->getProducts() as $product) {
            if ($product->isActive()) $html.=$product->getView()->categoryPageView();
        }
        return $html;
    }


    public function filterView() 
    {
        $html = "";
        foreach($this->category->getSubcategories() as $subcategory) {
            $html.="<div class='filter-item'>";
                $html.="<a class='subcategory-heading' href='/products/subcategories/?s=".$subcategory->getId()."'>".ucwords($subcategory->getName())."</a>";
                $html.="<div class='subcategory-links'>";
                foreach($subcategory->getValues() as $value) {
                    if ($value->getProductCount() > 0) {
                        $html.="<div >";
                            $html.="<a href='/products/subcategoryvalues/?s=".$value->getId()."'>".ucwords($value->getName())." (".$value->getProductCount().")</a>";
                        $html.="</div>";
                    }
                }
                $html.="</div>";
            $html.="</div>";
        }
        return $html;
    }


  
}
?>