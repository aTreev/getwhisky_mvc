<?php
require_once("crud/categorycrud.class.php");
require_once("views/categoryview.class.php");
require_once("productcontroller.class.php");
require_once("subcategorycontroller.class.php");

class CategoryController extends CategoryCRUD
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
        $categoryData = parent::getCategoryById($id)[0];

        if ($categoryData) {
            $this->setId($categoryData['id'])->setName($categoryData['name'])->setDescription($categoryData['description'])->setImage($categoryData['image'])->setProductCount($categoryData['product_count']);
            $this->checkForSubcategories();
            $categoryExists = true;
        }

        return $categoryExists;
    }

    public function initCategoryByName($name)
    {
        $categoryExists = false;
        $categoryData = parent::getCategoryByName($name)[0];
        
        if ($categoryData) {
            $this->setId($categoryData['id'])->setName($categoryData['name'])->setDescription($categoryData['description'])->setImage($categoryData['image'])->setProductCount($categoryData['product_count']);
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
    public function getProductsByOffsetLimit($offset=0, $limit=2)
    {
        
        $productController = new ProductController();
        $products = $productController->getProductsByCategoryId($this->getId(), $offset, $limit);
        if (!$products) return false;

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