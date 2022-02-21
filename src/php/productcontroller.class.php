<?php
require_once("crud/productcrud.class.php");
require_once("views/productview.class.php");

class ProductController extends ProductCRUD
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $price;
    private $discounted;
    private $discountPrice;
    private $discountEndDatetime;
    private $stock;
    private $purchases;
    private $active;
    private $dateAdded;
    private $alcoholVolume;
    private $bottleSize;
    private $type;
    private $categoryId;
    private $attributes = [];
    private $overviews = [];
    private $featured;

    private $productView;

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getDescription(){ return $this->description; }
    public function getImage(){ return $this->image; }
    public function getPrice(){ return $this->price; }
    public function isDiscounted(){ return $this->discounted; }
    public function getDiscountPrice(){ return $this->discountedPrice; }
    public function getDiscountEndDatetime(){ return $this->discountEndDatetime; }
    public function getStock(){ return $this->stock; }
    public function isActive(){ return $this->active; }
    public function getDateAdded(){ return $this->dateAdded; }
    public function getType(){ return $this->type; }
    public function getCategoryId(){ return $this->categoryId; }
    public function getAttributes(){ return $this->attributes; }
    public function getOverviews() { return $this->overviews; }
    public function isFeatured() { return $this->featured; }

    public function getView() { return $this->productView = new ProductView($this);}

    private function setId($id) { $this->id = $id; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($description) { $this->description = $description; return $this; }
    private function setImage($image) { $this->image = $image; return $this; }
    private function setPrice($price) { $this->price = $price; return $this; }
    private function setDiscounted($discounted) { $this->discounted = $discounted; return $this; }
    private function setDiscountPrice($discountedPrice) { $this->discountedPrice = $discountedPrice; return $this; }
    private function setDiscountEndDatetime($discountEndDatetime) { $this->discountEndDatetime = $discountEndDatetime; return $this; }
    private function setStock($stock) { $this->stock = $stock; return $this; }
    private function setActive($active) { $this->active = $active; return $this; }
    private function setDateAdded($dateAdded) { $this->dateAdded = $dateAdded; return $this; }
    private function setType($type) { $this->type = $type; return $this; }
    private function setCategoryId($categoryId) { $this->categoryId = $categoryId; return $this; }
    private function setAttributes($attributes) { $this->attributes = $attributes; return $this; }
    public function setFeatured($featured) { $this->featured = $featured; return $this; }

    public function __construct()
    {

    }


    public function getProductsByCategoryId($categoryid)
    {
        $products = parent::getProductsByCategoryIdModel($categoryid);
        return $products;
    }


    public function initProduct($id)
    {
        $productExists = false;
        $productData = parent::getProductByIdModel($id)[0];

        if ($productData) {
          
            $productExists = true;
            $this->setId($productData['id'])->setName($productData['name'])->setDescription($productData['description'])
            ->setImage($productData['image'])->setPrice($productData['price'])->setDiscounted($productData['discounted'])->setDiscountPrice($productData['discount_price'])
            ->setStock($productData['stock'])->setActive($productData['active'])->setDiscountEndDatetime($productData['discount_end_datetime'])->setDateAdded($productData['datetime_added'])
            ->setType($productData['type'])->setCategoryId($productData['category_id']);

        }

        return $productExists;
    }
}
?>