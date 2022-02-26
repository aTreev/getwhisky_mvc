<?php 
require_once("db.php");
class CategoryCRUD
{
    private static $db;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$db = db::getInstance();
    }


    protected function getCategoriesModel($style=MYSQLI_ASSOC) 
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT * FROM categories;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }
    
    protected function getCategoryById($id, $style=MYSQLI_ASSOC) 
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT categories.*, 
        (SELECT COUNT(DISTINCT products.name) FROM products WHERE products.category_id = ?) AS 'product_count' 
        FROM categories
        WHERE categories.id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("ii", $id, $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getCategoryByName($name, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT categories.*, 
                    (SELECT COUNT(DISTINCT products.name) FROM products WHERE products.category_id = (SELECT id FROM categories WHERE `name` = ?)) AS 'product_count' 
                    FROM categories
                    WHERE categories.name = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("ss", $name, $name);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getCategoryFiltersModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT `id`, `title` FROM filters WHERE filters.category_id = ? ORDER BY title ASC;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getFilterValuesModel($filterId, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT filter_values.id, `value`, 
        (SELECT COUNT(*) FROM filter_value_products WHERE filter_value_products.filter_value_id = filter_values.id) AS 'count_products_with_value' 
        FROM filter_values 
        JOIN filters
        ON filter_values.filter_id = filters.id
        
        WHERE filter_values.filter_id =? AND (SELECT COUNT(*) FROM filter_value_products WHERE filter_value_products.filter_value_id = filter_values.id) > 0;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $filterId);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }
}
?>