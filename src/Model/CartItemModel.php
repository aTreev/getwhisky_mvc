<?php
namespace Getwhisky\Model;

class CartItemModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getCartItemsByCartIdModel($cartid, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM `cart_item` WHERE `cart_id` = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $cartid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }


    protected function increaseCartItemQuantity($cartid, $productid, $quantity) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "UPDATE `cart_item` SET `quantity` = ( `quantity` + ? ) WHERE `cart_id` = ? AND `product_id` = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("isi", $quantity, $cartid, $productid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }
}

?>