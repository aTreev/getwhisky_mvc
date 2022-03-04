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

    private function bannerImage()
    {
        return "<div class='break-container hero-container'>
        <div class='hero-text'>
            <h1 class='text-white'>".ucwords($this->subcategory->getCategoryName())." ".ucwords($this->subcategory->getName())."</h1>
            <p class='text-white-faded'>".$this->subcategory->getDescription()."</p>
        </div>
        <img src='".$this->subcategory->getImage()."' class='hero-image' />
    </div>";
    }

    public function backwardsNavigation()
    {
        $html = "";
        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='container back-nav-content'>";
                $html.="<a href='/'>Home</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<a href='/categories/?c=".$this->subcategory->getCategoryId()."'>".ucwords($this->subcategory->getCategoryName())."</a>";
                $html.="<p class='caret'>&#8250;</p>";
                $html.="<p class='current-page'>".ucwords($this->subcategory->getCategoryName())." ".ucwords($this->subcategory->getName())."</p>";
            $html.="</div>";
        $html.="</div>";
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
        $title = ucwords($this->subcategory->getCategoryName())." ".ucwords($this->subcategory->getName());

        switch($this->subcategory->getName()) {
            case "distilleries": 
                $html = $this->distilleryPageView(); 
            break;
            case "regionss": 
                $html = $this->regionPageView(); 
            break;
            case "typess": 
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
        $html.= $this->backwardsNavigation();
        $html.=$this->bannerImage();
        return $html;
    }

    public function distilleryPageView()
    {
        $html = "";

        $html.= $this->backwardsNavigation();
        $html.=$this->bannerImage();

        $html.="<div class='distillery-items'>";
        foreach($this->subcategory->getValues() as $value) {
            if ($value->getProductCount() > 0) {
                $html.="<div class='distillery-item'>";
                    $html.="<img src='".$value->getThumbnail()."' />";
                    $html.="<p>".$value->getName()."</p>";
                    $html.="<a href='/categories/subcategories/subcategoryvalue/?s=".$value->getId()."'><span class='wrapper-link'></span></a>";
                $html.="</div>";
            }
            
        }
        $html.="</div>";
        return $html;
    }

}
?>