<?php
require_once("crud/categorycrud.class.php");
require_once("categoryview.class.php");
class CategoryController extends CategoryCRUD
{
    private $id;
    private $name;
    private $description;
    private $categoryView;

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
    public function getView() { return $this->categoryView = new CategoryView($this); }
    
    public function initCategory($id)
    {
        $categoryExists = false;
        $categoryData = parent::getCategoryById($id)[0];

        if ($categoryData) {
            $this->setId($categoryData['id'])->setName($categoryData['name'])->setDescription($categoryData['description']);
            
            $categoryExists = true;
        }

        return $categoryExists;
    }

    public function getCategories()
    {
        $categories = parent::getCategoriesModel();
        return $categories;
    }
}
?>