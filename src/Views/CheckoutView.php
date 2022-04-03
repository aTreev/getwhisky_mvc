<?php
namespace Getwhisky\Views;

use Getwhisky\Controllers\CheckoutController;

class CheckoutView
{
    // CheckoutController instance
    private CheckoutController $checkout;


    public function __construct($checkout)
    {
        $this->checkout = $checkout;
    }


    public function deliveryPage()
    {
        $html = "";
        $title = "Checkout | Getwhisky";
        $script = "/assets/js/checkout-delivery-page.js";
        $style = "/assets/style/checkout-pages.css";

        $cart = $this->checkout->getCart();
        $userAddresses = $this->checkout->getUser()->getAddresses();

        $html.=SharedView::backwardsNavigation(array(
            ['url' => '/basket', 'pageName' => 'My Basket'],
            ['url' => '', 'pageName' => "Checkout"]
        ));

        $html.="<div class='delivery-root'>";
            
            // User addresses
            $html.="<div class='address-container'>";
                $html.="<div class='content'>";
                    // header
                    $html.="<div class='header'>";
                        $html.="<h5>Choose delivery address</h5>";
                    $html.="</div>";
                    // Delivery info
                    $html.="<div class='info'>";
                        $html.="<p><b>Please Note:</b> We are currently only able to deliver to addresses within the UK. We apologize for any inconvenience.";
                    $html.="</div>";
                    $html.="<div class='info'>";
                        $html.="<p>Free delivery on orders over £".constant("free_delivery_threshold").". For all other orders a flat delivery fee of £".constant("delivery_cost")." will be applied during checkout.</p>";
                    $html.="</div>";
                    // User addresses
                    $html.="<div class='address-items'>";
                    foreach($userAddresses as $address) {
                        $html.=$address->getView()->deliveryItemView();
                    }            
                    $html.="</div>";
                    // Options
                    $html.="<div class='options'>";
                        // Add new address button
                        $html.="<button class='link-button' id='add-address-show'>Add new address</button>";
                        // Address id form
                        $html.="<form id='address-form' method='POST' action='/checkout/create-checkout-session'>";
                            $html.="<input type='hidden' value='' name='address-id' id='selected-address'>";
                        $html.="</form>";

                    $html.="</div>";

                $html.="</div>";
            $html.="</div>";



            // Cart summary
            $html.="<div class='cart-summary-container'>";
                $html.="<div class='content'>";
                    // Header
                    $html.="<div class='header'>";
                        $html.="<h5>Basket Summary</h5>";
                    $html.="</div>";

                    // Basket items - summary
                    $html.="<div class='basket-items'>";
                    foreach($cart->getItems() as $item) {
                        // basket item
                        $html.="<div class='item'>";
                            $html.="<div class='image-container'>";
                                $html.="<img src={$item->getProduct()->getImage()}>";
                            $html.="</div>";

                            $html.="<div class='details-container'>";
                                $html.="<p>{$item->getProduct()->getName()}</p>";
                                $html.="<p>Qty: {$item->getQuantity()}</p>";
                                $html.="<p>£{$item->getItemPrice()}</p>";
                            $html.="</div>";
                        $html.="</div>";
                    }
                        $html.="<div class='summary'>";
                            $html.="<p>Total: <span class='total'>£{$cart->getCartTotal()}</span></p>";
                        $html.="</div>";
                    $html.="</div>";

                $html.="</div>";
            $html.="</div>";

        $html.="</div>";

        $html.="<div class='add-address-root'>";
            $html.=$userAddresses[0]->getView()->createAddressForm();
        $html.="</div>";

        //$html.=$this->checkout->getUser()->getAddresses()[0]->getView()->createAddressForm();
        return [
            'html' => $html,
            'title' => $title,
            'script' => $script,
            'style' => $style
        ];
    }
}
?>