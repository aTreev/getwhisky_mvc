<?php
namespace Getwhisky\Model;
use Getwhisky\Model\DatabaseConnection;

class SubcategoryValueModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getSubcategoryValueIdsModel($subcategoryid, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
      
        $this->sql = "SELECT id FROM subcategory_value WHERE subcategory_id = ? ORDER BY name ASC;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $subcategoryid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getSubcategoryValueByIdModel($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
      
        $this->sql = "SELECT
                        subcategory_value.*, 
                        categories.name AS 'cat_name', 
                        categories.id AS 'cat_id',
                        subcategories.name AS 'subcat_name' 
                    FROM subcategory_value 
                    JOIN subcategories
                    ON subcategory_value.subcategory_id = subcategories.id
                    JOIN categories 
                    ON subcategories.category_id = categories.id
                    WHERE subcategory_value.id = ?";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductCountModel($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
        $this->sql = "SELECT COUNT(product_id) AS product_count FROM subcategory_value_product WHERE subcategory_value_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    
}

?>