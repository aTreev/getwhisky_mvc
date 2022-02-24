<?php
require_once("crud/categorycrud.class.php");
require_once("categoryview.class.php");
require_once("productcontroller.class.php");
class CategoryController extends CategoryCRUD
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $categoryView;
    private $productCount;
    private $filters = [];
    private $products = [];

    public function __construct()
    {
        //
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
    
    
    public function initCategory($id)
    {
        $categoryExists = false;
        $categoryData = parent::getCategoryById($id)[0];

        if ($categoryData) {
            $this->setId($categoryData['id'])->setName($categoryData['name'])->setDescription($categoryData['description'])->setImage($categoryData['image'])->setProductCount($categoryData['product_count']);
            $this->getCategoryFilters();
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
            $this->getCategoryFilters();
            $categoryExists = true;
        }

        return $categoryExists;
    }
    private function getCategoryFilters()
    {
        $filters = parent::getCategoryFiltersModel($this->getId());
        if (!$filters) return;

        foreach($filters as $filter) {
            $tmp['id'] = $filter['id'];
            $tmp['title'] = $filter['title'];
            array_push($this->filters, $tmp);
        }
        $this->getFilterValues();
        
        /*
        if($this->id == 1) {
            foreach($this->filters as $filter){
                echo var_dump($filter);
            }
        }
        */
    }

    private function getFilterValues()
    {
        $newFilters = [];

        //foreach filter
        foreach($this->filters as $filter) {
            // get filter values
            $filter['values'] = [];
            $valuesData = parent::getFilterValuesModel($filter['id']);
            if (!$valuesData) return;

            foreach($valuesData as $value) {
                if (!$value['id']) return;
                $tmp['id'] = $value['id'];
                $tmp['value'] = $value['value'];
                array_push($filter['values'], $tmp);
            }
            array_push($newFilters, $filter);
        }
        $this->filters = $newFilters;
        
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