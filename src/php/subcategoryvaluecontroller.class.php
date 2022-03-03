<?php
require_once("crud/subcategoryvaluecrud.class.php");
require_once("views/subcategoryvalueview.class.php");
class SubcategoryValueController extends SubcategoryValueCRUD
{
    private $id;
    private $subcategoryid;
    private $name;
    private $description;
    private $image;
    private $thumbnail;
    private $productCount;
    private $subcategoryValueView;
    private $products = [];

    public function __construct()
    {
        //
    }

    

    public function setId($id) { $this->id = $id; return $this; }
    public function setSubcategoryid($subcategoryid) { $this->subcategoryid = $subcategoryid; return $this; }
    public function setName($name) { $this->name = $name; return $this; }
    public function setDescription($description) { $this->description = $description; return $this; }
    public function setImage($image) { $this->image = $image; return $this; }
    public function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail;return $this; }
    private function setProductCount($productCount) { $this->productCount = $productCount;return $this; }

    public function getId(){ return $this->id; }
    public function getSubcategoryid() { return $this->subcategoryid; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getThumbnail() { return $this->thumbnail; }
    public function getProductCount() { return $this->productCount; }

    public function getView() { return $this->subcategoryValueView = new SubcategoryValueView($this); }
    public function getProducts() { return $this->products; }

    public function getSubcategoryValueIds($subcategoryid)
    {
        return parent::getSubcategoryValueIdsModel($subcategoryid);
    }

    public function initSubcategoryValue($id)
    {
        $exists = false;
        $data = parent::getSubcategoryValueByIdModel($id)[0];

        if ($data) {
            $exists = true;
            $this->setId($data['id'])->setSubcategoryid($data['subcategory_id'])->setName($data['name'])
            ->setDescription($data['description'])->setImage($data['image'])->setThumbnail($data['thumbnail']);
            
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

    public function loadProductsByOffsetLimit($offset, $limit)
    {
        $productData = parent::getProductsByOffsetLimit($this->getId(), $offset, $limit);
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
