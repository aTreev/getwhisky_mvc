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
            <a href='/products/category?c=".str_replace(" ", "_", $this->category->getName())."'>".$this->category->getName()."</a>
        </div>
            ";

        return $html;
    }

    public function categoryPage()
    {
        $html = "";
        $html.="
        <div class='break-container' style='background: rgba(0, 0, 0, 0.8)!important;'>
            <div style='max-width: 300px; position: absolute; left: 30%; top: 20%; transform: translate(-30%, -10%);z-index:4;'>
                <h1 style='color:white;'>".$this->category->getName()."</h1>
                <p style='color:white;'>".$this->category->getDescription()."</p>
            </div>
            <img src='".$this->category->getImage()."' style='width:100%;min-height: 200px;max-height: 300px;object-fit:cover;background: rgba(0, 0, 0, 0.8);opacity: 0.4;' />
        </div>

        <div class='break-container p-3 bg-white shadow-sm'>
            <div style='max-width:1280px;' class='m-auto d-flex align-items-center justify-content-between'>
            <div id='product-count'>Showing 5 of 5 products</div>
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
        $html.="</div>
        <style>
            .product {
               flex: 1;
               min-width: 250px;
               position: relative;
            }
            .product:hover {
                box-shadow: 2px 2px 15px lightgrey;
            }
            .product-image {
                max-height: 300px;
            }
            .product-name {
                margin-top: 10px;
                font-size: 18px;
                color: var(--bg-secondary);
            }
            .product-price {
                color: var(--bg-secondary);
                font-weight: 600;
            }
        </style>
        ";
        return $html;
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