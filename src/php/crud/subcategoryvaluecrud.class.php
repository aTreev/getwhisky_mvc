<?php
class SubcategoryValueCRUD
{
    private static $db;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$db = db::getInstance();
    }


    protected function getSubcategoryValueIdsModel($subcategoryid, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();
        
      
        $this->sql = "SELECT id FROM subcategory_value WHERE subcategory_id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $subcategoryid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getSubcategoryValueByIdModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();
        
      
        $this->sql = "SELECT * FROM subcategory_value WHERE id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductCountModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();
        
        $this->sql = "SELECT COUNT(product_id) AS product_count FROM subcategory_value_product WHERE subcategory_value_id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductsByOffsetLimit($id, $offset, $limit, $style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql = "  SELECT products.id 
                        FROM products
                        JOIN subcategory_value_product
                        ON subcategory_value_product.product_id = products.id
                        WHERE subcategory_value_product.subcategory_value_id = ? LIMIT ?,?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("iii", $id, $offset, $limit);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }
}

?>