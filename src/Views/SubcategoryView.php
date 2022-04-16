<?php
namespace Getwhisky\Views;

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
            $html.="<a href='/products/subcategories/?s=".$this->subcategory->getId()."' class='subcategory-menu-heading'>".$this->subcategory->getName()."</a>";
            // Break after 6 and add a view all >
            $i = 0;
            foreach($this->subcategory->getValues() as $subcatValue) {
                $html.=$subcatValue->getView()->menu();
                $i++;
                if ($i == 6) { 
                    $html.="<a href='/products/subcategories/?s=".$this->subcategory->getId()."'>See all ".$this->subcategory->getName()." &#8250; </a>";
                    break;
                }
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
        $title = ucwords($this->subcategory->getCategoryName())." ".ucwords($this->subcategory->getName());

        $html.= SharedView::backwardsNavigation(array(
            ['url' => "/products/categories/?c=".$this->subcategory->getCategoryId()."", 'pageName' => ucwords($this->subcategory->getCategoryName())],
            ['url' => '', 'pageName' => ucwords($this->subcategory->getCategoryName())." ".ucwords($this->subcategory->getName())]
        ));


        $html.=SharedView::bannerImage([
            'header' => ucwords($this->subcategory->getCategoryName()." ". ucwords($this->subcategory->getName())), 
            'text' => $this->subcategory->getDescription(), 
            'image' => $this->subcategory->getImage()
        ]);

        switch($this->subcategory->getName()) {
            default: 
                $html .= $this->defaultPageView(); 
            break;
            case "distilleries": 
                $html .= $this->distilleryPageView(); 
            break;
            case "regions": 
                $html .= $this->regionPageView(); 
            break;
            case "types": 
                $html .= $this->typePageView(); 
            break;
            
        }
        // Filters
        return ['html' => $html, 'style' => $style, 'script' => $script, 'title' => $title];
    }


    public function defaultPageView()
    {
        $html = "";
        return $html;
    }

    public function distilleryPageView()
    {
        $html = "";

        $html.="<div class='distillery-items'>";
        foreach($this->subcategory->getValues() as $value) {
            if ($value->getProductCount() > 0) {
                $html.="<div class='distillery-item'>";
                    $html.="<img src='".$value->getThumbnail()."' />";
                    $html.="<p>".$value->getName()."</p>";
                    $html.="<a href='/products/subcategoryvalues/?s=".$value->getId()."'><span class='wrapper-link'></span></a>";
                $html.="</div>";
            }
            
        }
        $html.="</div>";
        return $html;
    }

    public function regionPageView()
    {
        $html = "";
        $html.="<h4>Not implemented yet</h4>";
        return $html;
    }

    public function typePageView()
    {
        $html = "";
        $html.="<h4>Not implemented yet</h4>";
        return $html;
    }

}
?>