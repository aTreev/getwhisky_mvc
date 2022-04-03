<?php
namespace Getwhisky\Controllers;

use Getwhisky\Model\CheckoutModel;
use Getwhisky\Views\CheckoutView;

class CheckoutController extends CheckoutModel
{

    private UserController $user;
    private CartController $cart;
    private array $deliveryOptions;
    private CheckoutView $checkoutView;

    public function __construct($user, $cart)
    {
        $this->setUser($user);
        $this->setCart($cart);
        $this->setDeliveryOptions(parent::getDeliveryOptionsModel());
        //var_dump($this->getDeliveryOptions());
    }

    private function setUser($user) { $this->user = $user; }
    private function setCart($cart) { $this->cart = $cart; }
    private function setDeliveryOptions($options) { $this->deliveryOptions = $options; }

    public function getView() { return $this->checkoutView = new CheckoutView($this); }
    public function getUser() { return $this->user; }
    public function getCart() { return $this->cart; }
    public function getDeliveryOptions(){ return $this->deliveryOptions; }
}
?>