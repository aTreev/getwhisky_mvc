<?php
namespace Getwhisky\Controllers;
use Getwhisky\Model\ProductModel;
use Getwhisky\Util\Porter;
use Getwhisky\Views\ProductView;


class ProductController extends ProductModel
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $additionalImages = [];
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

    private $details = [];
    private $overviews = [];
    private $reviews = [];
    private $productView;

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getDescription(){ return $this->description; }
    public function getImage(){ return $this->image; }
    public function getAdditionalImages() { return $this->additionalImages; }
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
    public function getDetails() { return $this->details; }
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
    public function setFeatured($featured) { $this->featured = $featured; return $this; }

    public function __construct()
    {
        //
    }


    public function getProductsByCategoryId($categoryid, $offset, $limit, $sorting)
    {
        return parent::getProductsByCategoryIdModel($categoryid, $offset, $limit, $sorting);
        
    }

    public function getProductsBySubcategoryValueId($subcategoryValueId, $offset, $limit, $sorting)
    {
        return parent::getProductsBySubcategoryValueIdModel($subcategoryValueId, $offset, $limit, $sorting);
    }

    public function checkProductExists($id)
    {
        return parent::getProductByIdModel($id);
    }

    /*********
     * Takes a productid and initializes the product
     * returns false if product isn't found on DB
     * returns true if exists on DB
     *****/
    public function initProduct($id)
    {
        $productExists = false;
        $productData = parent::getProductByIdModel($id);

        if ($productData) {
            $productData = $productData[0];
            $productExists = true;

            $this->setId($productData['id'])->setName($productData['name'])->setDescription($productData['description'])
            ->setImage($productData['image'])->setPrice($productData['price'])->setDiscounted($productData['discounted'])->setDiscountPrice($productData['discount_price'])
            ->setStock($productData['stock'])->setActive($productData['active'])->setDiscountEndDatetime($productData['discount_end_datetime'])
            ->setType($productData['type'])->setCategoryId($productData['category_id'])->setAlcoholVolume($productData['alc_vol'])->setBottleSize($productData['bottle_size']);

            $this->getAdditionalDetails();
            $this->getProductOverviews();
            $this->getAdditionalProductImages();
            $this->checkDiscountEnded();
        }

        return $productExists;
    }


  
    private function getAdditionalDetails()
    {
        $detailData = parent::getProductAdditionalDetails($this->getId());

        if (!$detailData) return;

        foreach($detailData as $detail) {
            // Singularize subcategory name using Porter2 class
            $detailName = Porter::stem($detail['name']);
            
            array_push($this->details, [ 'name' => ucwords($detailName), 'value' => ucwords($detail['value']) ]);
        }
        
       //var_dump($this->details);
    }

  
    private function getAdditionalProductImages()
    {
        $images = parent::getAdditionalProductImagesModel($this->getId());

        if ($images) {
            $this->additionalImages = $images;
        }
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

    

    public function initProductByName($name)
    {
        $productExists = false;
        $productData = parent::getProductByNameModel($name);

        if ($productData) {
            $productData = $productData[0];
            $productExists = true;
            
            $this->setId($productData['id'])->setName($productData['name'])->setDescription($productData['description'])
            ->setImage($productData['image'])->setPrice($productData['price'])->setDiscounted($productData['discounted'])->setDiscountPrice($productData['discount_price'])
            ->setStock($productData['stock'])->setActive($productData['active'])->setDiscountEndDatetime($productData['discount_end_datetime'])
            ->setType($productData['type'])->setCategoryId($productData['category_id'])->setAlcoholVolume($productData['alc_vol'])->setBottleSize($productData['bottle_size']);

            $this->getAdditionalDetails();
            $this->getProductOverviews();
            $this->getAdditionalProductImages();
            $this->checkDiscountEnded();
        }

        return $productExists;
    }
}
?>