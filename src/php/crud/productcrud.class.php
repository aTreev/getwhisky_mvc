<?php
class ProductCRUD
{
    private static $db;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() {
        self::$db = db::getInstance();
    }


    protected function getProductsByCategoryIdModel($categoryid, $offset, $limit, $style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql = "SELECT * FROM products WHERE category_id = ? LIMIT ?,?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("iii", $categoryid, $offset, $limit);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductByIdModel($id, $style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql = "SELECT * FROM products WHERE id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }
}
?>