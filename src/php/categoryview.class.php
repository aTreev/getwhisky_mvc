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
            <a href='/products/category?c=".$this->category->getId()."'>".$this->category->getName()."</a>
        ";

        return $html;
    }
}
?>