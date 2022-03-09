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

        $html.="<tr>";
            // Product details
            $html.="<td>";
                $html.="<div class='cart-item-details-container'>";

                    $html.="<div class='cart-item-img-container'>";
                        $html.="<img src='".$product->getImage()."' style='width:100%;'/>";
                    $html.="</div>";

                    $html.="<div class='cart-item-details'>";
                        $html.="<p>".ucwords($product->getName())."</p>";
                        $html.="<p>".ucwords($product->getType())."</p>";
                        $html.="<button class='remove-item'>Remove</button>";
                    $html.="</div>";

                $html.="</div>";
            $html.="</td>";

            // Product Quantity
            $html.="<td>";
                $html.="<div class='cart-item-quantity-container'>";
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
            $html.="</td>";

            // Price
            $html.="<td>";
                if($product->isDiscounted()) {
                    $html.="<div class='price-discount-container'>";
                        $html.="<p class='price-inactive'>£".$product->getPrice()."</p>";
                        $html.="<p class='price-active'>£".$product->getDiscountPrice()."</p>";
                    $html.="</div>";
                } else {
                    $html.="<p class='price-active'>£".$product->getActivePrice()."</p>";
                }
            $html.="</td>";

            // subtotal
            $html.="<td>";
                $html.="<div class='cart-item-subtotal-container'>";
                    $html.="<p>£".($this->cartItem->getQuantity() * $product->getPrice())."</p>";
                $html.="</div>";
            $html.="</td>";
        $html.="</tr>";

        return $html;
    }

    public function itemView1()
    {
        $product = $this->cartItem->getProduct();
        $quantityLimit = ($product->getStock() < constant("purchase_limit")) ? $quantityLimit = $product->getStock() : constant("purchase_limit");
        $html = "";

        $html.="<div class='cart-item' style='display:flex;'>";

            $html.="<div class='cart-item-details-container' style='max-width:450px;display:flex;gap:10px;'>";

                $html.="<img src='".$product->getImage()."' style='width: 30%;'/>";

                $html.="<div class='cart-item-details'>";
                    $html.="<p>".ucwords($product->getName())."</p>";
                    $html.="<p>".ucwords($product->getType())."</p>";
                $html.="</div>";
            $html.="</div>";

            $html.="<div class='cart-item-quantity-container'>";
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

            $html.="<div class='cart-item-price-container'>";
                if($product->isDiscounted()) {
                    $html.="<div class='price-discount-container' style='display:flex;gap:5px;'>";
                        $html.="<p>£".$product->getPrice()."</p>";
                        $html.="<p>£".$product->getDiscountPrice()."</p>";
                    $html.="</div>";
                } else {
                    $html.="<p>£".$product->getActivePrice()."</p>";
                }
            $html.="</div>";

            $html.="<div class='cart-item-subtotal-container'>";
                $html.="<p>£".($this->cartItem->getQuantity() * $product->getPrice())."</p>";
            $html.="</div>";
        $html.="</div>";

        return $html;
    }
}
?>