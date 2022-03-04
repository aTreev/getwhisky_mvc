<?php
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
            $html.="<a href='/categories?c=".str_replace(" ", "-", $this->category->getId())."' categoryid='".$this->category->getId()."'>".$this->category->getName()."</a>";

            $html.="<div class='subcategory-menus'>";
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

    public function categoryPageFullView()
    {
        $script = "/assets/js/category-page.js";
        $style = "/assets/style/category-page.css";
        $title = ucwords($this->category->getName());
        $filterView = $this->filterView();

        $html = "";

        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='container back-nav-content'>";
                $html.="<a href='/'>Home</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<p class='current-page'>".ucwords($this->category->getName())."</p>";
            $html.="</div>";
        $html.="</div>";

        $html.="
        <div class='break-container hero-container'>
            <div class='hero-text'>
                <h1 class='text-white'>".ucwords($this->category->getName())."</h1>
                <p class='text-white-faded'>".$this->category->getDescription()."</p>
            </div>
            <img src='".$this->category->getImage()."' class='hero-image' />
        </div>

        <div class='break-container p-3 bg-white shadow-sm'>
            <div class='container px-2 m-auto d-flex align-items-center justify-content-between gap-1 flex-wrap'>
            <div>Showing <span id='product-count'>".count($this->category->getProducts())."</span> of ".$this->category->getProductCount()." products</div>
            <button class='btn btn-danger' id='open-filters'><i class='fa-solid fa-filter'></i> Product Filters</button>
            </div>
        </div>
        <input type='hidden' id='category' value='".$this->category->getName()."'>
        ";
        // Filters
        $html.="
            <div id='filter-root' class='product-filters'>
                <div class='bg-light p-3 text-center d-flex align-items-center'>
                    <i class='fa-solid fa-xmark me-5 site-icon-black' id='close-filters'></i>
                    <h4 style='font-weight:300'>Product Filters</h4>
                </div>
                <div>
                    $filterView
                </div>
                <div>
                    <button name='sort-option' id=''>Latest</button>
                    <button name='sort-option' id='asc'>price (low)</button>
                    <button name='sort-option' id='desc'>price (high)</button>
                </div>
            </div>
        ";

        // Products
        $html.="<div id='product-root' class='m-auto mt-5 d-flex flex-row flex-wrap gap-4'>";
        foreach($this->category->getProducts() as $product) {
            if ($product->isActive()) $html.=$product->getView()->categoryPageView();
        }
        $html.="</div>";

        return ['html' => $html, 'script' => $script, 'style' => $style, 'title' => $title];
    }


    public function productsOnly()
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
                $html.="<a class='subcategory-heading' href='/categories/subcategories/?s=".$subcategory->getId()."'>".ucwords($subcategory->getName())."</a>";
                $html.="<div class='subcategory-links'>";
                foreach($subcategory->getValues() as $value) {
                    if ($value->getProductCount() > 0) {
                        $html.="<div >";
                            $html.="<a href='/categories/subcategories/subcategoryvalue/?s=".$value->getId()."'>".ucwords($value->getName())."</a>";
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