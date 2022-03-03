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
        $html = "";
        $html.="<div class='subcategory-menu' category='".$this->subcategory->getCategoryId()."'>";
            $html.="<a href='/categories/subcategories?s=".$this->subcategory->getId()."' class='subcategory-menu-heading'>".$this->subcategory->getName()."</a>";
            foreach($this->subcategory->getValues() as $subcatValue) {
                $html.=$subcatValue->getView()->menu();
            }
        return $html;
    }


    /*******
     * Full view subcategory page
     * Calls specific page view depending on subcategory name
     * If subcategory name not supported defaults to a generic view
     ********************/
    public function subcategoryPageFullView()
    {
        $script = "";
        $style = "/assets/style/distillery-page.css";
        $html = "";
        $title = "";

        switch($this->subcategory->getName()) {
            case "Distilleries": 
                $html = $this->distilleryPageView(); 
            break;
            case "region": 
                $html = $this->regionPageView(); 
            break;
            case "type": 
                $html = $this->typePageView(); 
            break;
            default: 
                $html = $this->defaultPageView(); 
            break;
        }
        // Filters
        return ['html' => $html, 'style' => $style, 'script' => $script, 'title' => $title];
    }


    public function defaultPageView()
    {
        $html = "";
        $html.="<a href='/categories?c=".$this->subcategory->getCategoryId()."'>Back to category</a>";
        $html.=$this->subcategory->getName();
        return $html;
    }

    public function distilleryPageView()
    {
        $html = "";
        
        $html.="
        <div class='break-container' style='background: rgba(0, 0, 0, 0.8)!important;'>
            <div class='hero-text'>
                <h1 class='text-white'>".ucwords($this->subcategory->getName())."</h1>
                <p class='text-white-faded'>".$this->subcategory->getDescription()."</p>
            </div>
            <img src='".$this->subcategory->getImage()."' class='hero-image' />
        </div>";

        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='container'>";
                $html.="<a href='/categories/subcategories?s=".$this->subcategory->getCategoryId()."'>Back to category</a>";
            $html.="</div>";
        $html.="</div>";
        
        $html.="<div class='distillery-items'>";
        foreach($this->subcategory->getValues() as $value) {
            $html.="<div class='distillery-item'>";
                $html.="<img src='".$value->getThumbnail()."' />";
                $html.="<p>".$value->getName()."</p>";
                $html.="<a href='/categories/subcategories/subcategoryvalue/?s=".$value->getId()."'><span class='wrapper-link'></span></a>";
            $html.="</div>";
        }
        $html.="</div>";
        return $html;
    }

}
?>