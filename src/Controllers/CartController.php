<?php
namespace Getwhisky\Controllers;

use Exception;
use Getwhisky\Model\CartModel;
use Getwhisky\Controllers\CartItemController;
use Getwhisky\Util\UniqueIdGenerator;
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
    public function getNotifications() { return $this->notifications; }
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
        // return if guest session for now
        if ($userid == -1) return;

        // User cart exists, init
        if ($userCart) {
            $userCart = $userCart[0];

            $this->setId($userCart['id'])->setUserid($userCart['user_id'])->setCheckedOut($userCart['checked_out']);
            $this->loadCartItems();
            
            // Check for stock notification cookies and then check stock
            if (isset($_COOKIE['cart-notifications'])) $this->notifications = json_decode($_COOKIE['cart-notifications'], true);
            $this->checkStock();
        } 
        

        // No user cart
        if (!$userCart) {
            // Get unique ID
            $uniqueIdGen = new UniqueIdGenerator();
            $cartid = $uniqueIdGen->setIdProperties(parent::getCartIds())->getUniqueId();
            // Create new cart
            parent::createUserCart($cartid, $userid);
            // Recursive call to retrieve cart
            //$this->initCart($userid);
            
            
        }
        return $userCart;
    }


    /*********
     * Checks the stock of each cart item
     * Sets a notification via cookies
     * Updates the quantity or removes the item entirely depending on stock
     **************/
    public function checkStock()
    {
        foreach($this->items as $item) {
            // Stock less than quantity
            if ($item->getProduct()->getStock() == 0) {

                // set array key by product_id ensures unique
                $this->notifications[$item->getProduct()->getId()] = [
                    'image' => $item->getProduct()->getImage(), 
                    'desc' => ucwords($item->getProduct()->getName())." removed from cart due to stock shortages"
                ];

                $this->removeFromCart($item->getProduct()->getId());

            }
            // Out of stock
            else if ($item->getProduct()->getStock() < $item->getQuantity()) {
                // set array key by product_id ensures unique
                $this->notifications[$item->getProduct()->getId()] = [
                    'image' => $item->getProduct()->getImage(), 
                    'desc' => ucwords($item->getProduct()->getName())." quantity changed to ".$item->getProduct()->getStock()." due to stock shortages"
                ];
                
                $this->updateItemQty($item, $item->getProduct()->getStock());
            }
        }
        if (!empty($this->notifications)) setcookie("cart-notifications", json_encode($this->notifications), "", "/");
    }


    /**********
     * Clears any set notification cookies
     *************/
    public function clearNotifications()
    {
        unset($this->notifications);
        setCookie("cart-notifications", "", time() - 3600, "/");
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
        // Default message with error condition
        $result = ['result' => false, 'message' => "Failed to add product to basket, please try again"];

        // Attempt item insert
        $insert = parent::addToCartModel($this->getId(), $productid, $quantity);

        // if successful insert
        if ($insert) {
            // Add new CartItemController to items array
            $item = new CartItemController();
            $item->initCartItemByAddToCart($this->getId(), $productid, $quantity);
            array_push($this->items, $item);
            // Success message
            $result = ['result' => true, 'message' => $item->getProduct()->getName()." added to basket"];
            
        }
      
        return $result;
    }


    public function addToCart($productid, $quantity)
    {
        $result = ['result', 'message'];

        // Guard clause - ensure quantity at least 1
        if ($quantity < 1) return['result' => 0, 'message' => "Quantity must be greater than 0"];
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

    /*********
     * Deletes a cart item where the productid matches
     ***********/
    private function deleteCartItemByProductId($productid) 
    {
        for($i = 0; $i <= count($this->items); $i++) {
            if ($this->items[$i]->getProduct()->getId() == $productid) {
                unset($this->items[$i]);
            }
        }
    }

    /********
     * Removes an item from cart
     * checks if an item exists and if the item is in cart
     * returns fail or success message
     ***********/
    public function removeFromCart($productid)
    {
        // Guard Clause - Check whether product exists with temp controller
        if (!(new ProductController)->checkProductExists($productid)) return ['result' => 0, 'message' => "Invalid product supplied"];

        // Check if product is in cart
        $cartItem = $this->findProductInCart($productid);
        // Guard clause - return fail if not in cart
        if (!$cartItem) return ['result' => 0, 'message' => "Product not in basket"];

        // Delete from DB
        $result = parent::removeFromCartModel($this->getId(), $productid);

        // If sucessfull DB delete remove object
        if ($result) {
            $this->deleteCartItemByProductId($productid);
            
            return ['result' => 1, 'message' => 'Product removed from basket', 'cartCount' => $this->getItemCount()];
        }

        // DB query fail
        return ['result' => 0, 'message' => "Something went wrong"];
    }


    private function updateItemQty($cartItem, $quantity)
    {
        return $cartItem->updateItemQuantity($quantity);
    }

    /**********
     * Updates an existing items quantity via the CartItemController
     * Checks if quantity is at least one
     * Checks if product exists
     * Checks if product in cart
     * returns success or fail message
     */
    public function updateCartItemQuantity($productid, $quantity)
    {
        // Guard clause - ensure quantity at least 1
        if ($quantity < 1) return['result' => 0, 'message' => "Quantity must be greater than 0"];

        // Guard Clause - Check whether product exists with temp controller
        if (!(new ProductController)->checkProductExists($productid)) return ['result' => 0, 'message' => "Invalid product supplied"];

        // Check and get item if product is in cart
        $cartItem = $this->findProductInCart($productid);
        // Guard clause - return fail if not in cart
        if (!$cartItem) return ['result' => 0, 'message' => "Product not in basket"];

        $result = $this->updateItemQty($cartItem, $quantity);
        $result['cartCount'] = $this->getItemCount();

        return $result;
    }
}
?>