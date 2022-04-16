<?php
namespace Getwhisky\Controllers;

use Getwhisky\Model\CheckoutModel;
use Getwhisky\Views\CheckoutView;

class CheckoutController extends CheckoutModel
{

    private  $user;
    private CartController $cart;
    private CheckoutView $checkoutView;

    public function __construct($user, $cart, $order=null)
    {
        $this->setUser($user);
        $this->setCart($cart);
        $this->setOrder($order);
    }

    private function setUser($user) { $this->user = $user; }
    private function setCart($cart) { $this->cart = $cart; }
    private function setOrder($order) { $this->order = $order; }

    public function getView() { return $this->checkoutView = new CheckoutView($this); }
    public function getUser() { return $this->user; }
    public function getCart() { return $this->cart; }
    public function getOrder() { return $this->order; }
}
?>