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

        $this->sql = "SELECT * FROM products WHERE category_id = ? ORDER BY id DESC LIMIT ?,?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("iii", $categoryid, $offset, $limit);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }


    protected function getProductByIdModel($id, $style=MYSQLI_ASSOC) 
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT * FROM products WHERE id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getAdditionalProductImagesModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT `image` FROM product_extra_images WHERE product_id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductByNameModel($name, $style=MYSQLI_ASSOC) 
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT * FROM products WHERE name = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("s", $name);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }


    protected function getProductFiltersModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT filters.title, filter_values.value
        FROM filters
        JOIN filter_values
        ON filters.id = filter_values.filter_id
        JOIN filter_value_products
        ON filter_value_products.filter_value_id = filter_values.id
        WHERE filter_value_products.product_id = ?
        ORDER BY filters.title ASC;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductOverviewsModel($id, $style=MYSQLI_ASSOC)
    {
        self::$db = db::getInstance();

        $this->sql = "SELECT id, image, heading, long_text FROM product_overviews WHERE product_id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function endProductDiscount($id)
    {
        self::$db = db::getInstance();

        $this->sql = "UPDATE products SET discounted = 0, discount_price = NULL, discount_end_datetime = NULL WHERE id = ?;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

}
?>