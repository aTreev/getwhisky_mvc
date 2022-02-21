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
            $html.="<div class='product-menu-item' categoryid='".$this->category->getId()."'>";
                $html.="<a href='/products/category?c=".str_replace(" ", "_", $this->category->getName())."'>".$this->category->getName()."</a>";
            $html.="</div>";

        return $html;
    }

    public function index()
    {
        $html = "";
        foreach($this->category->getProducts() as $product) {
            if ($product->isActive()) $html.=$product->getView()->categoryPageView();
        }
        return $html;
    }
}
?>