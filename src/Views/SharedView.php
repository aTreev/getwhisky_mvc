<?php
namespace Getwhisky\Views;

class SharedView {

    /*****
     * Returns the html for the sites backwards navigation
     * Takes an assoc array with keys 'url' and 'pageName'
     * Iterates through the array creating links for all keys 
     * and a plaintext p tag for the last.
     * "/" "home" provided by default
     *********************************/
    public static function backwardsNavigation($backLinks)
    {
        $html = "";
        $html.="<div class='backwards-navigation break-container'>";
            $html.="<div class='container px-2 back-nav-content'>";
                $html.="<a href='/'>Home</a>";
                $html.="<p class='caret'>&#8250;</p>";
            foreach($backLinks as $key => $element) {
                if ($key === array_key_last($backLinks)) {
                    $html.="<p class='current-page'>".$element['pageName']."</p>";
                } else {
                   $html.="<a href='".$element['url']."'>".$element['pageName']."</a>";
                    $html.="<p class='caret'>&#8250;</p>"; 
                }
            }
            $html.="</div>";
        $html.="</div>";
        return $html;
    }

    /**********
     * Contructs the site's shared banner image html
     * Takes an assoc with header, text & image
     *****************/
    public static function bannerImage($banner=['header', 'text', 'image'])
    {
        $html = "";

        $html.="<div class='break-container hero-container'>";
            $html.="<div class='hero-text'>";
                $html.="<h1 class='text-white'>".$banner['header']."</h1>";
                $html.="<p class='text-white-faded'>".$banner['text']."</p>";
            $html.="</div>";
            $html.="<img src='".$banner['image']."' class='hero-image'/>";
        $html.="</div>";

        return $html;
    }
}
?>