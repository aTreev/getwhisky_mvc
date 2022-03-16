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
        var_dump($this->removeFromCart(36));
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


    /******
     * Retrieves and returns the total number of items
     * in the cart.
     ****/
    public function getItemCount()
    {
        $itemCount = 0;
        foreach($this->items as $item) {
            $itemCount += $item->getQuantity();
        }

        return $itemCount;
    }

    /******
     * Retrieves and returns the total price of the cart
     *****/
    public function getCartTotal()
    {
        $total = 0;
        foreach($this->items as $item) {
            $total += $item->getItemPrice() * $item->getQuantity();
        }
        return number_format($total, 2, '.', '');
    }

    /*******
     * Retrieves and returns any discounts applied
     * to the cart
     ********/
    public function getCartDiscounts()
    {
        $discount = 0;
        foreach($this->items as $item) {
            $discount += $item->checkDiscount();
        }
        
        return number_format($discount, 2, '.', '');
    }


    /***********
     * Takes a productid and iterates through the cartitems to
     * check if the product is already in the cart
     * Returns the item if it's found
     * Returns false if not found
     ********/
    private function findProductInCart($productid)
    {
        foreach($this->items as $item) {
            if ($item->getProduct()->getId() == $productid) {
                return $item;
            }
        }

        return false;
    }

    /***********
     * Increases an existing cart item's quantity via the
     * CartItemController.
     * Receives and returns a success or fail message
     ************/
    private function increaseCartItemQty($cartItem, $quantity)
    {
        return $cartItem->increaseItemQuantity($quantity);
    }



    /*********
     * Adds a new item to the cart via the Cart's Model
     * returns a success or fail message
     *********/
    private function addNewItemToCart($productid, $quantity)
    {
        $result = ['result' => false, 'message' => "Failed to add product to basket, please try again"];
        $insert = parent::addToCartModel($this->getId(), $productid, $quantity);

        if ($insert) {
            $item = new CartItemController();
            $item->initCartItemByAddToCart($this->getId(), $productid, $quantity);
            array_push($this->items, $item);
            $result = ['result' => true, 'message' => $item->getProduct()->getName()." added to basket"];
            
        }
      
        return $result;
    }


    public function addToCart($productid, $quantity)
    {
        $result = ['result', 'message'];

        // Guard Clause - Check whether product exists with new controller
        if (!(new ProductController)->checkProductExists($productid)) return ['result' => false, 'message' => "Invalid product supplied"];

        // Check if product is in cart
        $cartItem = $this->findProductInCart($productid);

        // Call appropriate method depending on whether in cart
        if ($cartItem) $result = $this->increaseCartItemQty($cartItem, $quantity);
        if (!$cartItem) $result = $this->addNewItemToCart($productid, $quantity); 
        
        // Append cartCount to return var
        $result['cartCount'] = $this->getItemCount();

        return $result;
    }
}
?>