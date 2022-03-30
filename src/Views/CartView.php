<?php
namespace Getwhisky\Views;

class CartView
{
    private $cart;

    public function __construct($cart)
    {
        $this->setCart($cart);        
    }


    private function setCart($cart) {$this->cart = $cart; }



    private function emptyCart()
    {
        $html = "";

        $html.="<div class='no-items-container'>";  
            $html.="<h4>Your Basket is empty</h4>";
            $html.="<p>Add to your cart from our lovely range of products and come back to complete your purchase!</p>";
            $html.="<a href='/'>Continue Shopping</a>";
        $html.="</div>";

        return $html;
    }

    private function cartHasItems()
    {
        $html = "";

        // Cart Header
        $html.="<div class='cart-root'>";

            $html.="<div class='cart-left'>";

                $html.="<div class='cart-header'>";
                    $html.="<h2>My Shopping Basket</h2>";
                $html.="</div>";

                $html.="<div class='cart-headings'>";
                    $html.="<p>Product Details</p>";
                    $html.="<p>Quantity</p>";
                    $html.="<p>Price</p>";
                    $html.="<p>Subtotal</p>";

                $html.="</div>";

                $html.="<div class='cart-items-container'>";
                    foreach($this->cart->getItems() as $item) {
                        $html.=$item->getView()->itemView();
                    }
                $html.="</div>";

            $html.="</div>";

            // cart right -- basket summary
            $html.="<div class='cart-right'>";

                // header
                $html.="<div class='cart-header'>";
                    $html.="<h2>Basket Summary</h2>";
                $html.="</div>";

                // headings same as left
                $html.="<div class='cart-headings me-5'>";
                    $html.="<p>Details</p>";
                $html.="</div>";

                // summary details
                $html.="<div class='summary-details'>";
                    $html.="<ul>";
                        $html.="<li>Discounts:</li>";
                        $html.="<li>Items:</li>";
                        $html.="<li>Total:</li>";

                    $html.="</ul>";

                    $html.="<ul>";
                        $html.="<li>£".$this->cart->getCartDiscounts()."</li>";
                        $html.="<li>".$this->cart->getItemCount()."</li>";
                        $html.="<li>£".$this->cart->getCartTotal()."</li>";
                    $html.="</ul>";
                $html.="</div>";

                // Proceed button
                $html.="<button class='btn btn-success px-5 mt-3'>Secure Checkout</button>";
           
            $html.="</div>";
        $html.="</div>";
        return $html;
    }

    /*********
     * WORK ON THIS.
     */
    private function cartNotifications()
    {   
        $html= "";

        $html.="<div class='cart-notifications'>";
            $html.="<div class='header'>";
            $html.="<p>You have notifications about your cart</p>";
            $html.="<i class='fas fa-times' id='clear-notifications'></i>";
            $html.="</div>";
        foreach($this->cart->getNotifications() as $notification) {
            $html.="<div class='cart-notification'>";
                $html.="<img src='".$notification['image']."' />";
                $html.="<p>".$notification['desc']."</p>";
            $html.="</div>";
        }
        $html.="</div>";

        return $html;
    }

    public function cartPage()
    {
        $html = "";
        $title = "My Basket | Getwhisky";
        $script = "/assets/js/cart-page.js";
        $style = "/assets/style/cart-page.css";

        $html.=SharedView::backwardsNavigation(array(
            ['url' => '', 'pageName' => "My basket"]
        ));

        if ($this->cart->getNotifications()) {
            $html.= $this->cartNotifications();
            $this->cart->clearNotifications();
        }
        if ($this->cart->getItems()) $html.= $this->cartHasItems();
        if (!$this->cart->getItems()) $html.= $this->emptyCart();

        
        return [
            'html' => $html,
            'title' => $title,
            'script' => $script,
            'style' => $style
        ];
    }
    
}
?>