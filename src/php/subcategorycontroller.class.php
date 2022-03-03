<?php
require_once("crud/subcategorycrud.class.php");
require_once("views/subcategoryview.class.php");
require_once("subcategoryvaluecontroller.class.php");
class SubcategoryController extends SubcategoryCRUD
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $values = [];
    private $subcategoryView;
    
    /******
     * backwards nav variables
     */
    private $categoryid;
    private $categoryName;

    public function __construct()
    {
        //
    }


    private function setId($id) { $this->id = $id; return $this; }
    private function setCategoryId($categorid) { $this->categoryid = $categorid; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($desc) { $this->description = $desc; return $this; }
    private function setImage($image) { $this->image = $image; return $this; }
    private function setCategoryName($categoryName) { $this->categoryName = $categoryName; return $this; }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getValues() { return $this->values; }
    public function getView() { return $this->subcategoryView = new SubcategoryView($this); }
    public function getCategoryId() { return $this->categoryid; }
    public function getCategoryName() { return $this->categoryName; }

    public function getSubcategoryIds($categoryid)
    {
        return parent::getSubcategoryIdsModel($categoryid);
    }

    public function initSubcategoryById($id)
    {
        $exists = false;
        $data = parent::getSubcategoryByIdModel($id)[0];
        if ($data) {
            $this->setId($data['id'])->setCategoryId($data['category_id'])->setName($data['name'])
            ->setDescription($data['description'])->setImage($data['image'])->setCategoryName($data['cat_name']);

            $this->getSubcategoryValues();
            $exists = true;
        }
        
        return $exists;
    }

    private function getSubcategoryValues()
    {
        $tmp = new SubcategoryValueController();
        $values = $tmp->getSubcategoryValueIds($this->getId());
        if ($values){
            foreach($values as $value) {
                $tmpObj = new SubcategoryValueController();
                $tmpObj->initSubcategoryValue($value['id']);
                array_push($this->values, $tmpObj);
            }
        }
    }

   
}
?>