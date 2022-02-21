<?php
require_once("crud/categorycrud.class.php");
require_once("categoryview.class.php");
require_once("productcontroller.class.php");
class CategoryController extends CategoryCRUD
{
    private $id;
    private $name;
    private $description;
    private $categoryView;
    private $filters = [];
    private $products = [];

    public function __construct()
    {
        //
    }


    private function setId($id) { $this->id = $id; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($desc) { $this->description = $desc; return $this; }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getFilters() { return $this->filters; }
    public function getView() { return $this->categoryView = new CategoryView($this); }
    public function getProducts() { return $this->products; }
    
    
    public function initCategory($id)
    {
        $categoryExists = false;
        $categoryData = parent::getCategoryById($id)[0];

        if ($categoryData) {
            $this->setId($categoryData['id'])->setName($categoryData['name'])->setDescription($categoryData['description']);
            $this->getCategoryFilters();
            $this->getCategoryProducts();
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

    public function getCategoryProducts()
    {
        $productController = new ProductController();
        $products = $productController->getProductsByCategoryId($this->getId());
        if (!$products) return;

        foreach($products as $product) {
            $productObj = new ProductController();
            $productObj->initProduct($product['id']);
            array_push($this->products, $productObj);
        }
    }

    public function getCategories()
    {
        $categories = parent::getCategoriesModel();
        return $categories;
    }
}
?>