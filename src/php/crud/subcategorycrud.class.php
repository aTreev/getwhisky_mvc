<?php
class SubcategoryCRUD
{
    private static $db;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$db = db::getInstance();
    }


    protected function getSubcategoryIdsModel($table, $categoryid, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();
        // extra injection protection
        switch ($table) {
            case "p_distilleries": $table = $table; break;
            case "p_types": $table = $table; break;
            case "p_regions": $table = $table; break;
            default: return array(0); break;
        }
        $this->sql = "SELECT id FROM $table WHERE category_id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $categoryid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getSubcategoryDetailsModel($table, $id, $style=MYSQLI_ASSOC) 
    {
        self::$db = db::getInstance();
        // extra injection protection
        switch ($table) {
            case "p_distilleries": $table = $table; break;
            case "p_types": $table = $table; break;
            case "p_regions": $table = $table; break;
            default: return array(0); break;
        }

        $this->sql = "SELECT * FROM $table WHERE id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    

    protected function checkProductCountModel($column, $id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        // extra injection protection
        switch ($column) {
            case "distillery_id": $column = $column; break;
            case "type_id": $column = $column; break;
            case "region_id": $column = $column; break;
            default: return array(0); break;
        }
        $this->sql = "SELECT COUNT(product_id) AS 'product_count' FROM product_attributes WHERE $column = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }
}

?>