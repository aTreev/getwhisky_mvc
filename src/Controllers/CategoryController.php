<?php
namespace Getwhisky\Controllers;
use Getwhisky\Model\CategoryModel;
use Getwhisky\Views\CategoryView;

class CategoryController extends CategoryModel
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $categoryView;
    private $productCount;
    private $products = [];

    // Subcategories
    private $subcategories = [];

    public function __construct()
    {
        // class='items-$this->getName()'
    }


    private function setId($id) { $this->id = $id; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($desc) { $this->description = $desc; return $this; }
    private function setImage($image) { $this->image = $image; return $this; }
    private function setProductCount($productCount) { $this->productCount = $productCount; return $this; }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getFilters() { return $this->filters; }
    public function getView() { return $this->categoryView = new CategoryView($this); }
    public function getProducts() { return $this->products; }
    public function getImage() { return $this->image; }
    public function getProductCount() { return $this->productCount; }
    public function getSubcategories() { return $this->subcategories; }
    
    public function initCategory($id)
    {
        $categoryExists = false;
        $data = parent::getCategoryById($id);

        if ($data) {
            $data = $data[0];
            $this->setId($data['id'])->setName($data['name'])->setDescription($data['description'])->setImage($data['image'])->setProductCount($data['product_count']);
            $this->checkForSubcategories();
            $categoryExists = true;
        }

        return $categoryExists;
    }

    public function initCategoryByName($name)
    {
        $categoryExists = false;
        $data = parent::getCategoryByName($name);
        
        if ($data) {
            $data = $data[0];
            $this->setId($data['id'])->setName($data['name'])->setDescription($data['description'])->setImage($data['image'])->setProductCount($data['product_count']);
            $this->checkForSubcategories();
            $categoryExists = true;
        }
        return $categoryExists;
    }


    private function checkForSubcategories()
    {
        $tmp = new SubcategoryController();
        $subcategoryIds = $tmp->getSubcategoryIds($this->getId());
        if ($subcategoryIds) {
          foreach($subcategoryIds as $subcategoryId) {
                $tmpObj = new SubcategoryController();
                $tmpObj->initSubcategoryById($subcategoryId['id']);
                array_push($this->subcategories, $tmpObj);
            }  
        }
        
    }



    /*****************
     * retrieves category's products
     * 
     * Returns false on the following conditions
     *  @offset is equal to the product count meaning no more
     *  products to retrieve
     * 
     *  No products retrieved
     */
    public function getProductsByOffsetLimit($offset=0, $limit=20, $sorting=null)
    {
        
        $productController = new ProductController();
        $products = $productController->getProductsByCategoryId($this->getId(), $offset, $limit);
        if (!$products) return false;

        if ($sorting) {
            if ($sorting == "asc") uasort($products, function($data1, $data2){return $data1['price'] <=> $data2['price'];});
            if ($sorting == "desc") uasort($products, function($data1, $data2){return $data2['price'] <=> $data1['price'];});
        }
        foreach($products as $product) {
            $productObj = new ProductController();
            $productObj->initProduct($product['id']);
            array_push($this->products, $productObj);
        }

        return true;
    }

    public function getCategories()
    {
        $categories = parent::getCategoriesModel();
        return $categories;
    }
}
?>