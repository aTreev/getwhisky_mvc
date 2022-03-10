<?php
namespace Getwhisky\Views;

class CartItemView
{
    private $cartItem;

    public function __construct($cartItem)
    {
        $this->setCartItem($cartItem);
    }

    private function setCartItem($cartItem) { $this->cartItem = $cartItem; }


    public function itemView()
    {
        $product = $this->cartItem->getProduct();
        $quantityLimit = ($product->getStock() < constant("purchase_limit")) ? $quantityLimit = $product->getStock() : constant("purchase_limit");
        $html = "";

        $html.="<div class='cart-item' product-id='".$product->getId()."'>";

            // Details
            $html.="<div class='cart-item-details-container'>";
                $html.="<a href='/categories/subcategories/subcategoryvalue/product?p=".$product->getName()."'>";
                $html.="<img src='".$product->getImage()."' />";
                $html.="</a>";
                $html.="<div class='cart-item-details'>";
                    $html.="<p>".ucwords($product->getName())."</p>";
                    $html.="<p>".ucwords($product->getType())."</p>";
                    $html.="<button id='remove-item-".$product->getId()."'>Remove</button>";
                $html.="</div>";
            $html.="</div>";

            // Quantity
            $html.="<div class='quantity-container'>";
                $html.="<select>";
                for($i = 1; $i <= $quantityLimit; $i++) {
                    if ($i == $this->cartItem->getQuantity()) {
                        $html.="<option value='$i' selected>$i</option>";
                    } else {
                        $html.="<option value='$i'>$i</option>";
                    }
                }
                $html.="</select>";
            $html.="</div>";

            // Price
            $html.="<div class='price-container'>";
                if($product->isDiscounted()) {
                    $html.="<div class='price-discount-container'>";
                        $html.="<p class='price-inactive'>£".$product->getPrice()."</p>";
                        $html.="<p class='price-active'>£".$product->getDiscountPrice()."</p>";
                    $html.="</div>";
                } else {
                    $html.="<p class='price-active'>£".$product->getActivePrice()."</p>";
                }
            $html.="</div>";

            // Subtotal
            $html.="<div class='subtotal-container'>";
                $html.="<p>£".($this->cartItem->getQuantity() * $product->getPrice())."</p>";
            $html.="</div>";
        $html.="</div>";

        

        return $html;
    }
}
?>