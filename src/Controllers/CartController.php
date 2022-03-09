<?php
namespace Getwhisky\Controllers;

use Getwhisky\Model\CartModel;
use Getwhisky\Controllers\CartItemController;
use Getwhisky\Views\CartView;

class CartController extends CartModel
{
    private string $id;
    private string $userid;
    private bool $checkedOut;
    private array $notifications = [];
    private array $items = [];
    private CartView $cartView;


    public function __construct()
    {
        
    }

    private function setId($id) { $this->id = $id; return $this; }
    private function setUserid($userid) { $this->userid = $userid; return $this; }
    private function setCheckedOut($checkedOut) { $this->checkedOut = $checkedOut; return $this; }

    public function getId() { return $this->id; }
    public function getUserid() { return $this->userid; }    
    public function isCheckedOut() { return $this->checkedOut; }
    public function getItems() { return $this->items; }
    public function getView() { return $this->CartView = new CartView($this); }



    /*********************
     * Takes in a userid from the Page controller
     * Checks for an active cart on the database
     * If cart is found it is constructed and items
     * retrieved
     * If no cart is found it creates one and does a
     * recursive call to construct the newly created cart
     * 
     * Every initiation the checkItems function is called to
     * retrieve correct pricings and update stocks in the event
     * of low stock.
     ************/
    public function initCart($userid)
    {
        $userCart = parent::checkForCart($userid);
        if ($userCart) {
            $userCart = $userCart[0];

            $this->setId($userCart['id'])->setUserid($userCart['user_id'])->setCheckedOut($userCart['checked_out']);
            $this->loadCartItems();
        }
        return $userCart;
    }

    /**************
     * Creates a temp controller to retrieve the cart item data
     * Loops through the retrieved data and instantiates cart items
     * using the init function.
     * Pushes each cartItem to the cartItems array
     ***************************/
    private function loadCartItems()
    {
        $tmpController = new CartItemController();

        $itemData = $tmpController->getCartItemsByCartId($this->getId());

        if (!$itemData) return;

        foreach($itemData as $item) {
            $cartItem = new CartItemController();
            $cartItem->initCartItem($item);
            array_push($this->items, $cartItem);
        }

    }
}
?>