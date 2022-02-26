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
    private $active;
    private $dateAdded;
    private $alcoholVolume;
    private $bottleSize;
    private $type;
    private $featured;
    private $categoryId;
    private $filters = [];
    private $overviews = [];
    private $reviews = [];

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
    public function getAlcoholVolume(){ return $this->alcoholVolume; }
    public function getBottleSize() { return $this->bottleSize; }
    public function getType(){ return $this->type; }
    public function getCategoryId(){ return $this->categoryId; }
    public function getFilters(){ return $this->filters; }
    public function getOverviews() { return $this->overviews; }
    public function getReviews() { return $this->reviews; }
    public function isFeatured() { return $this->featured; }

    public function getView() { return $this->productView = new ProductView($this);}

    public function getActivePrice() { return $this->isDiscounted() ? $this->getDiscountPrice() : $this->getPrice(); }

    private function setId($id) { $this->id = $id; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($description) { $this->description = $description; return $this; }
    private function setImage($image) { $this->image = $image; return $this; }
    private function setPrice($price) { $this->price = number_format((float)$price, 2, ".", ""); return $this; }
    private function setDiscounted($discounted) { $this->discounted = $discounted; return $this; }
    private function setDiscountPrice($discountedPrice) { $this->discountedPrice = $discountedPrice; return $this; }
    private function setDiscountEndDatetime($discountEndDatetime) { $this->discountEndDatetime = $discountEndDatetime; return $this; }
    private function setStock($stock) { $this->stock = $stock; return $this; }
    private function setActive($active) { $this->active = $active; return $this; }
    private function setAlcoholVolume($alcVol) { $this->alcoholVolume = $alcVol; return $this; }
    private function setBottleSize($bottleSize) { $this->bottleSize = $bottleSize; return $this;}
    private function setType($type) { $this->type = $type; return $this; }
    private function setCategoryId($categoryId) { $this->categoryId = $categoryId; return $this; }
    private function setFilters($filters) { $this->filters = $filters; return $this; }
    public function setFeatured($featured) { $this->featured = $featured; return $this; }

    public function __construct()
    {

    }


    public function getProductsByCategoryId($categoryid, $offset, $limit)
    {
        $products = parent::getProductsByCategoryIdModel($categoryid, $offset, $limit);
        return $products;
    }

    public function getFilteredProducts($filters, $offset, $limit)
    {
        $products = parent::getFilteredProductsModel($filters, $offset, $limit);
        return $products;
    }

    private function getProductFilters()
    {
        $filters = parent::getProductFiltersModel($this->getId());
        if (!$filters) return;
        $this->filters = $filters;
    }

    private function getProductOverviews()
    {
        $overviews = parent::getProductOverviewsModel($this->getId());
        if (!$overviews) return;
        $this->overviews = $overviews;
    }

    // Checks whether a discount has reached its end datetime
    // Ends the discount on database & object state
    private function checkDiscountEnded()
    {
        if (!$this->isDiscounted()) return;

        if (strtotime($this->getDiscountEndDatetime()) < time()) {
            parent::endProductDiscount($this->getId());
            $this->setDiscounted(false);
        }
    }

    public function initProduct($id)
    {
        $productExists = false;
        $productData = parent::getProductByIdModel($id)[0];

        if ($productData) {
          
            $productExists = true;
            $this->setId($productData['id'])->setName($productData['name'])->setDescription($productData['description'])
            ->setImage($productData['image'])->setPrice($productData['price'])->setDiscounted($productData['discounted'])->setDiscountPrice($productData['discount_price'])
            ->setStock($productData['stock'])->setActive($productData['active'])->setDiscountEndDatetime($productData['discount_end_datetime'])
            ->setType($productData['type'])->setCategoryId($productData['category_id'])->setAlcoholVolume($productData['alc_vol'])->setBottleSize($productData['bottle_size']);

            $this->getProductFilters();
            $this->getProductOverviews();
            $this->checkDiscountEnded();
        }

        return $productExists;
    }

    public function initProductByName($name)
    {
        $productExists = false;
        $productData = parent::getProductByNameModel($name)[0];

        if ($productData) {
            $productExists = true;
            $this->setId($productData['id'])->setName($productData['name'])->setDescription($productData['description'])
            ->setImage($productData['image'])->setPrice($productData['price'])->setDiscounted($productData['discounted'])->setDiscountPrice($productData['discount_price'])
            ->setStock($productData['stock'])->setActive($productData['active'])->setDiscountEndDatetime($productData['discount_end_datetime'])
            ->setType($productData['type'])->setCategoryId($productData['category_id'])->setAlcoholVolume($productData['alc_vol'])->setBottleSize($productData['bottle_size']);

            $this->getProductFilters();
            $this->getProductOverviews();
            $this->checkDiscountEnded();
        }

        return $productExists;
    }
}
?>