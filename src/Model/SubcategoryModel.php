<?php
namespace Getwhisky\Model;
use Getwhisky\Model\DatabaseConnection;

class SubcategoryModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getSubcategoryIdsModel($categoryid, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
      
        $this->sql = "SELECT id FROM subcategories WHERE category_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $categoryid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getSubcategoryByIdModel($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
      
        $this->sql = "  SELECT subcategories.*, categories.name AS 'cat_name' FROM subcategories 
                        JOIN categories
                        ON subcategories.category_id = categories.id
                        WHERE subcategories.id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    
}

?>