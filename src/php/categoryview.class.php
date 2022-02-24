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
        $html = "
        <div class='product-menu-item' categoryid='".$this->category->getId()."'>
            <a href='/products/category?c=".str_replace(" ", "-", $this->category->getName())."'>".$this->category->getName()."</a>
        </div>
            ";

        return $html;
    }

    public function categoryPageFullView()
    {
        $script = "/assets/js/category-page.js";
        $style = "/assets/style/category-page.css";
        $title = $this->category->getName();

        $html = "";
        $html.="
        <div class='break-container' style='background: rgba(0, 0, 0, 0.8)!important;'>
            <div class='hero-text'>
                <h1 class='text-white'>".$this->category->getName()."</h1>
                <p class='text-white-faded'>".$this->category->getDescription()."</p>
            </div>
            <img src='".$this->category->getImage()."' class='hero-image' />
        </div>

        <div class='break-container p-3 bg-white shadow-sm'>
            <div style='max-width:1280px;' class='px-2 m-auto d-flex align-items-center justify-content-between flex-wrap'>
            <div>Showing <span id='product-count'>".count($this->category->getProducts())."</span> of ".$this->category->getProductCount()." products</div>
            <button class='btn btn-danger'><i class='fa-solid fa-filter'></i> Product Filters</button>
            </div>
        </div>
        <input type='hidden' id='category' value='".$this->category->getName()."'>
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
}
?>