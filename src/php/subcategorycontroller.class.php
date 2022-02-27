<?php
require_once("crud/subcategorycrud.class.php");
require_once("views/subcategoryview.class.php");
class SubcategoryController extends SubcategoryCRUD
/**************
 * Subcategory class
 * Comprised of a limited number of hardcoded tables
 * Currently supports:
 *          Distillery, Region, Type
 */
{
    private $id;
    private $name;
    private $description;
    private $thumbnail;
    private $image;
    private $productCount;
    private $subcategoryView;
    
    // PHP only instance variable
    // Used to query the correct column
    private $columName;
    
    public function __construct()
    {
        //
    }

    private function setId($id) { $this->id = $id; return $this; }
    private function setName($name) { $this->name = $name; return $this; }
    private function setDescription($description) { $this->description = $description; return $this; }
    private function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail; return $this; }
    private function setImage($image) { $this->image = $image; return $this; }
    private function setProductCount($productCount) { $this->productCount = $productCount; return $this; }
    private function setColumnName($columnName) { $this->columnName = $columnName; return $this; }

    public function getId() { return $this->id; }
    public function getName(){ return $this->name; }
    public function getDescription() { return $this->name; }
    public function getThumbnail() { return $this->thumbnail; }
    public function getImage() { return $this->image; }
    public function getProductCount() { return $this->productCount; }
    public function getColumnName() { return $this->columnName; }
    public function getView() { return $this->subcategoryView = new SubcategoryView($this); }



    /************************************************************************************************************************************************************ 
     * These two methods need to be updated when adding support for more subcategory types
    ***/
    public function getSubcategoryDetails($subcategoryType, $categoryid)
    {
        $table = "";
        switch($subcategoryType) {
            case "distillery": $table = "p_distilleries"; break;
            case "region":  $table = "p_regions"; break;
            case "type": $table = "p_types"; break;
        }

        return parent::getSubcategoryIdsModel($table, $categoryid);
    }
    
    public function initSubcategoryById($subcategoryType, $id)
    {
        $data = "";
        $columnName = "";
        $table = "";
        switch($subcategoryType) {
            case "distillery":
                $table = "p_distilleries";
                $columnName = "distillery_id";
            break;
            case "region":
                $table = "p_regions";
                $columnName = "region_id";
            break;
            case "type":
                $table = "p_types";
                $columnName = "type_id";
            break;
        }
        $data = parent::getSubcategoryDetailsModel($table, $id)[0];
        if (!$data) return 0;

        $this->setId($data['id'])->setName($data['name'])->setDescription($data['description'])
        ->setImage($data['image'])->setThumbnail($data['thumbnail'])->setColumnName($columnName)->setProductCount($this->checkProductCount());
        return 1;
    }
    /********************************************************************************************************************************************************** */

    private function checkProductCount()
    {
        return parent::checkProductCountModel($this->getColumnName(), $this->getId())[0]['product_count'];
    }
}
?>