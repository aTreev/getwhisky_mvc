<?php
require_once("crud/subcategoryvaluecrud.class.php");
require_once("views/subcategoryvalueview.class.php");
class SubcategoryValueController extends SubcategoryValueCRUD
{
    private $id;
    
    private $name;
    private $description;
    private $image;
    private $thumbnail;
    private $productCount;
    private $subcategoryValueView;
    private $products = [];

    /*******
     * breadcrumb details
     */
    private $categoryid;
    private $categoryName;
    private $subcategoryid;
    private $subcategoryName;
    public function __construct()
    {
        //
    }

    

    public function setId($id) { $this->id = $id; return $this; }
    public function setName($name) { $this->name = $name; return $this; }
    public function setDescription($description) { $this->description = $description; return $this; }
    public function setImage($image) { $this->image = $image; return $this; }
    public function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail;return $this; }
    private function setProductCount($productCount) { $this->productCount = $productCount;return $this; }
    public function setSubcategoryid($subcategoryid) { $this->subcategoryid = $subcategoryid; return $this; }
    private function setSubcategoryName($subcategoryName) { $this->subcategoryName = $subcategoryName; return $this; }
    private function setCategoryId($categoryid) { $this->categoryid = $categoryid; return $this; }    
    private function setCategoryName($categoryName) { $this->categoryName = $categoryName; return $this; }

    public function getId(){ return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getThumbnail() { return $this->thumbnail; }
    public function getProductCount() { return $this->productCount; }
    public function getSubcategoryid() { return $this->subcategoryid; }
    public function getSubcategoryName() { return $this->subcategoryName; }    
    public function getCategoryId() { return $this->categoryid; }
    public function getCategoryName() { return $this->categoryName; }

    public function getView() { return $this->subcategoryValueView = new SubcategoryValueView($this); }
    public function getProducts() { return $this->products; }

    public function getSubcategoryValueIds($subcategoryid)
    {
        return parent::getSubcategoryValueIdsModel($subcategoryid);
    }

    public function initSubcategoryValue($id)
    {
        $exists = false;
        $data = parent::getSubcategoryValueByIdModel($id);

        if ($data) {
            $data = $data[0];
            $exists = true;
            $this->setId($data['id'])->setSubcategoryid($data['subcategory_id'])->setName($data['name'])
            ->setDescription($data['description'])->setImage($data['image'])->setThumbnail($data['thumbnail'])
            ->setSubcategoryName($data['subcat_name'])->setCategoryId($data['cat_id'])->setCategoryName($data['cat_name']);
            
            // get product count
            $this->getSubcategoryValuesProductCount();
        }
        return $exists;
    }


    private function getSubcategoryValuesProductCount()
    {
        $productCount = parent::getProductCountModel($this->getId())[0];
        $this->setProductCount($productCount['product_count']);
    }


    /************
     * Loads subcategoryvalue's products by offset,limit
     * optionally takes a sorting option for sorting by price
     * Retrieves Ids and instaniates new products for the class
     *******************************/
    public function loadProductsByOffsetLimit($offset, $limit, $sorting=null)
    {
        $productData = parent::getProductsByOffsetLimit($this->getId(), $offset, $limit);

        if ($sorting) {
            if ($sorting == "asc") uasort($productData, function($data1, $data2){return $data1['price'] <=> $data2['price'];});
            if ($sorting == "desc") uasort($productData, function($data1, $data2){return $data2['price'] <=> $data1['price'];});
        }
        
        foreach($productData as $product) {
            $tmpObj = new ProductController();
            $tmpObj->initProduct($product['id']);
            array_push($this->products, $tmpObj);
        }
        //if ($this->getId() != 1) return;
        //var_dump($this->products);
    }
}
?>
