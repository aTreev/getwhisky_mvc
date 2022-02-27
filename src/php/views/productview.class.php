<?php
class ProductView
{
    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function categoryPageView()
    {
        $html = "
        <div class='product-c bg-white text-center'>
            <img src='".$this->product->getImage()."' class='product-c-image img-fluid' loading='lazy'/>
            <p class='product-c-name'>".$this->product->getName()."</p>
            <p class='text-uppercase text-muted'>".$this->product->getType()."</p>
            ";
        if ($this->product->getAlcoholVolume()) {
            $html.="<p class='text-muted'>".$this->product->getBottleSize()." / ".$this->product->getAlcoholVolume()."</p>";
        }
        $html.="
            <p class='product-c-price'>£".$this->product->getPrice()."</p>
            <a href='/products/product?p=".str_replace(" ", "-", $this->product->getName())."' class='wrapper-link'><span></span></a>
        </div>
        ";

        return $html;
    }

    public function productPageFullView()
    {
        $script = "/assets/js/product-page.js";
        $style = "/assets/style/product-page.css";
        $title = ucwords($this->product->getName());
        $maxAllowed = $this->product->getStock() < 10 ? $this->product->getStock() : 10;
        $html = "";

        $html.="<div class='product'>";

            // Container for all things product images
            $html.="<div class='product-left'>";
                // Main product Image
                $html.="<img src='".$this->product->getImage()."' alt='".$this->product->getName()."' class='product-image' id='main-image' />";
                $html.="<div class='image-popup-modal'>";
                    $html.="<img src='' id='popup-image'/>";
                $html.="</div>";
                // Image gallery
                $html.="<div class='gallery-images'>";
                    // Main currently active image
                    $html.="<img src='".$this->product->getImage()."' alt='".$this->product->getName()."' class='gallery-image gallery-image-selected' name='gallery-image'/>";
                    foreach($this->product->getAdditionalImages() as $image) {
                        $html.="<img src='".$image['image']."' alt='".$this->product->getName()."' class='gallery-image' name='gallery-image'/>";
                    }
                $html.="</div>";
            $html.="</div>";

            // Main product details
            $html.="<div class='product-right'>";
                $html.="<div class='product-right-top'>";
                if ($this->product->isDiscounted()) $html.="<p class='sale'>sale</p>";
                    $html.="<h1 class='product-name'>".$this->product->getName()."</h1>";
                    $html.="<h5 class='product-type' >".$this->product->getType()."</h5>";
                if ($this->product->getAlcoholVolume()) {
                    $html.="<p class='text-muted fs-5'>".$this->product->getBottleSize()." / ".$this->product->getAlcoholVolume()."</p>";
                }
                    
                $html.="<div class='product-price-section'>";
                    if ($this->product->isDiscounted()) {
                        $html.="<div class='discount-timer-container'>";
                            $html.="<p class='sale-text'>Limited-time offer, ends in: </p>";
                            $html.="<p class='sale-timer' id='discount-endtime' end='".strtotime($this->product->getDiscountEndDatetime())."'></p>";
                        $html.="</div>";
                        $html.="<div class='product-price-container'>";
                            $html.="<p class='inactive-price'>£".$this->product->getPrice()."</p>";
                            $html.="<p class='product-price'>£".$this->product->getDiscountPrice()."</p>";
                        $html.="</div>";
                    } else {
                        $html.="<div class='product-price-container'>";
                            $html.="<p class='product-price'>£".$this->product->getPrice()."</p>";
                        $html.="</div>";
                    }
                    
            
                $html.="</div>";
                if ($this->product->getStock() > 0) {
                    $html.="<select class='quantity-selection'>";
                for($i = 1; $i <= $maxAllowed; $i++) {
                    $html.="<option value=$i>$i</option>";
                }
                    $html.="</select>";
                    $html.="<button class='add-to-cart-btn' id='add-to-cart'>Add to basket</button>";
                } else {
                    $html.="<select class='quantity-selection' disabled>";
                    $html.="</select>";
                    $html.="<button class='add-to-cart-btn out-of-stock-btn'>Out of stock</button>";
                }
                $html.="</div>";
                $html.="<p class='product-description'>".$this->product->getDescription()."</p>";


                // Tabs with desc / reviews / details
                $html.="<div class='product-right-bottom'>";
                    $html.="<div class='tabs-container'>";
                        $html.="<div class='tabs'>";
                            $html.="<button class='active' tab='tab-details'>Details</button>";
                            $html.="<button tab='tab-description'>Description</button>";
                            $html.="<button tab='tab-reviews'>Reviews</button>";
                        $html.="</div>";
                        // Details
                        $html.="<div id='tab-details' class='tab-content'>";
                            if ($this->product->getFilters()) {
                                foreach($this->product->getFilters() as $filter) {
                                    $html.="<div class='detail-item'>";
                                        $html.="<p>".$filter['title']."</p>";
                                        $html.="<p>".$filter['value']."</p>";
                                    $html.="</div>";
                                }
                            } else {
                                $html.="<p style='font-style: italic;padding: 20px; opacity: 0.8;'>Details currently unavailable</p>";
                            }   
                        $html.="</div>";

                        // Descriptions
                        $html.="<div id='tab-description' class='tab-content'>";
                        if ($this->product->getOverviews()) {
                            foreach($this->product->getOverviews() as $overview) {
                                $html.="<div class='description-item'>";
                                    $html.="<h3>".$overview['heading']."</h3>";
                                    $html.="<p>".$overview['long_text']."</p>";
                                    if ($overview['image']) $html.= "<img src='".$overview['image']."' />";
                                $html.="</div>";
                            }
                        } else {
                            $html.="<p style='font-style: italic;padding: 20px; opacity: 0.8;'>Description currently unavailable</p>";
                        }
                        $html.="</div>"; 

                        // reviews
                        $html.="<div id='tab-reviews' class='tab-content'>";
                        if ($this->product->getReviews()) {
                            //
                        } else {
                            $html.="<p style='font-style: italic;padding: 20px; opacity: 0.8;'>Reviews currently unavailable</p>";
                        }
                        $html.="</div>"; 
                    $html.="</div>";
                $html.="</div>";

            $html.="</div>";
        $html.="</div>";
     


        return ['html' => $html, 'script' => $script, 'style' => $style, 'title' => $title];
    }
}
?>