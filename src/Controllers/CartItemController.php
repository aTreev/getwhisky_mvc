<?php
namespace Getwhisky\Controllers;

use Getwhisky\Controllers\ProductController;
use Getwhisky\Model\CartItemModel;
use Getwhisky\Views\CartItemView;

class CartItemController extends CartItemModel
{
    private string $cartid;
    private int $productid;
    private int $quantity;
    private ProductController $product;
    private CartItemView $cartItemView;


    private function setCartId($cartid) { $this->cartid = $cartid; return $this; }
    private function setProductId($productid) { $this->productid = $productid; return $this; }
    private function setQuantity($quantity) { $this->quantity = $quantity; return $this; }
    private function setProduct(ProductController $product) { $this->product = $product; return $this; }

    public function getCartId() { return $this->cartid; }
    public function getProductId() { return $this->productid; }
    public function getQuantity() { return $this->quantity; }
    public function getProduct() { return $this->product; }
    public function getView() { return $this->cartItemView = new CartItemView($this); }

    public function __construct()
    {
        
    }


    /********
     * Returns the IDs of cart items to the
     * calling class for instantiation through
     * the calling class
     */
    public function getCartItemsByCartId($cartid)
    {
        return parent::getCartItemsByCartIdModel($cartid);
    }


    // Initializes the cart item using data passed from the cart
    // Creates a new instance of a product controller using the
    // cart item data product_id
    public function initCartItem($itemData)
    {
        $this->setCartId($itemData['cart_id'])->setProductId($itemData['product_id'])->setQuantity($itemData['quantity']);
        $productController = new ProductController();
        $productController->initProduct($this->getProductId());
        $this->setProduct($productController);        
    }

    public function initCartItemByAddToCart($cartid, $productid, $quantity)
    {
        $this->setCartId($cartid)->setProductId($productid)->setQuantity($quantity);
        $productController = new ProductController();
        $productController->initProduct($this->getProductId());
        $this->setProduct($productController);      
    }

    //
    public function getItemPrice()
    {
        return $this->product->getActivePrice();
    }

    // Checks the product instance for any discount
    // and returns it
    public function checkDiscount()
    {
        if (!$this->getProduct()->isDiscounted()) return 0;

        return ($this->getProduct()->getPrice() - $this->getProduct()->getDiscountPrice()) * $this->getQuantity();
    }


    /********
     * Increases the CartItem's quantity
     * Returns a success or fail message depending
     * on result.
     *********/
    public function increaseItemQuantity($quantity)
    {
        // Guard clause - return fail message if new quantity higher than stock
        if ($this->getQuantity() + $quantity > $this->getProduct()->getStock()) return ['result' => false, 'message' => "Insufficient stock to add specified quantity"];

        $result = parent::increaseCartItemQuantity($this->getCartId(), $this->productid, $quantity);

        // Successful update
        if ($result) {
            // Update object quantity
            $this->setQuantity($this->getQuantity()+$quantity);
            // Return success message
            return ['result' => true, 'message' => ucwords($this->getProduct()->getName())." basket quantity updated"];
        } else {
            // Failed, return failure message
            return ['result' => false, 'message' => "Something went wrong, please try again"];
        }
    }


    /***********
     * Updates the CartItems quantity
     * Checks that sufficient product stock exists
     * Checks whether quantity changed at all
     * Returns empty, fail or success message 
     * depending on result
     */
    public function updateItemQuantity($quantity)
    {
        // Guard clauses

        // Quantity greater than stock return fail message
        if ($quantity > $this->getProduct()->getStock()) return ['result' => false, 'message' => "Insufficient stock to update quantity"];

        // Quantity not changed return no message
        if ($quantity == $this->getQuantity()) return ['result' => 0, 'message' => ""];

        $result = parent::updateCartItemQuantity($this->getCartId(), $this->getProductId(), $quantity);

        // Successful update
        if ($result) {
            // Update object quantity
            $this->setQuantity($quantity);
            // Return success message
            return ['result' => true, 'message' => ucwords($this->getProduct()->getName())." basket quantity updated"];
        } else {
            // Failed, return failure message
            return ['result' => false, 'message' => "Something went wrong, please try again"];
        }
    }
}
?>