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
    public function initCartItem($itemData)
    {
        $this->setCartId($itemData['cart_id'])->setProductId($itemData['product_id'])->setQuantity($itemData['quantity']);
        $productController = new ProductController();
        $productController->initProduct($this->getProductId());
        $this->setProduct($productController);        
    }
}
?>