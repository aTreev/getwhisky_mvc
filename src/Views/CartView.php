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
    /***********
     * Cart page
     */

    public function index()
    {
        $html = "";
        $title = "My Basket - Getwhisky";
        $script = "";
        $style = "/assets/style/cart-page.css";
        $html.="<h2>My Shopping Basket</h2>";

        $html.="<table class='cart-items-table'>";
            $html.="<thead>";
                $html.="<tr>";
                    $html.="<th>Product Details</th>";
                    $html.="<th>Quantity</th>";
                    $html.="<th>Price</th>";
                    $html.="<th>Subtotal</th>";
                $html.="</tr>";
            $html.="</thead>";
            
            $html.="<tbody>";
            foreach($this->cart->getItems() as $item) {
                $html.=$item->getView()->itemView();
            }
            $html.="</tbody>";
        $html.="</table>";
        return ['html' => $html, 'title' => $title, 'script' => $script, 'style' => $style];
    }



    public function index1()
    {
        $html = "";
        $title = "My Basket - Getwhisky";
        $script = "";
        $style = "";
        $html.="<h2>My Shopping Basket</h2>";

        $html.="<div>";

            $html.="<div class='cart-items-header' style='display:flex; justify-content:space-between;'>";
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

        return ['html' => $html, 'title' => $title, 'script' => $script, 'style' => $style];
    }
}
?>