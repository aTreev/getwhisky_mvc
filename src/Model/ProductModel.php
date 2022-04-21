<?php
namespace Getwhisky\Model;
use Getwhisky\Model\DatabaseConnection;

class ProductModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getProductsByCategoryIdModel($categoryid, $offset, $limit, $sorting, $style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM products WHERE category_id = ?";

        switch($sorting) {
            case "asc":     $this->sql.= " ORDER BY price ASC LIMIT ?,?;";   break;
            case "desc":    $this->sql.= " ORDER BY price DESC LIMIT ?,?;";  break;
            default:        $this->sql.=" ORDER BY id DESC LIMIT ?,?;"; break;
        }
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("iii", $categoryid, $offset, $limit);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductsBySubcategoryValueIdModel($id, $offset, $limit, $sorting, $style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "  SELECT products.id, products.price 
                        FROM products
                        JOIN subcategory_value_product
                        ON subcategory_value_product.product_id = products.id
                        WHERE subcategory_value_product.subcategory_value_id = ?";

        switch($sorting) {
            case "asc":     $this->sql.= " ORDER BY products.price ASC LIMIT ?,?;";   break;
            case "desc":    $this->sql.= " ORDER BY products.price DESC LIMIT ?,?;";  break;
            default:        $this->sql.=" ORDER BY products.id DESC LIMIT ?,?;"; break;
        }
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("iii", $id, $offset, $limit);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductByIdModel($id, $style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM products WHERE id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getAdditionalProductImagesModel($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT `image` FROM product_extra_images WHERE product_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductByNameModel($name, $style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM products WHERE name = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $name);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductAdditionalDetails($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "  SELECT subcategories.name, subcategory_value.name AS 'value'
                        FROM subcategories
                        JOIN subcategory_value 
                        ON subcategory_value.subcategory_id = subcategories.id
                        JOIN subcategory_value_product
                        ON subcategory_value_product.subcategory_value_id = subcategory_value.id
                        WHERE subcategory_value_product.product_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getProductOverviewsModel($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT id, image, heading, long_text FROM product_overviews WHERE product_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function endProductDiscount($id)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "UPDATE products SET discounted = 0, discount_price = NULL, discount_end_datetime = NULL WHERE id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $id);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function decrementStockModel($id, $quantity)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "UPDATE products SET stock = (stock - ?) WHERE id = ?";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ii", $quantity, $id);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

}
?>