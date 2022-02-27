<?php
class SubcategoryView 
{
    private $subcategory;
    
    public function __construct($subcategory)
    {
        $this->setSubcategory($subcategory);  
    }

    private function setSubcategory($subcategory) { $this->subcategory = $subcategory; }


    // return menu html only if subcategory has products
    public function menu() 
    {
        if ($this->subcategory->getProductCount() == 0) return "";
        return "<a href='/products/subcategory?s=".str_replace(" ", "-",$this->subcategory->getName())."'>".$this->subcategory->getName()."</a>";
        
    }
}
?>