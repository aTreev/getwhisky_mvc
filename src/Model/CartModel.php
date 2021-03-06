<?php
namespace Getwhisky\Model;

class CartModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }

    protected function checkForCart($userid, $style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM `cart` WHERE `user_id` = ? AND `checked_out` = 0;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $userid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getCartIds($style=MYSQLI_ASSOC) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT id FROM `cart`;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function createUserCart($id, $userid)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "INSERT INTO cart (`id`, `user_id`) VALUES (?,?);";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ss", $id, $userid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function addToCartModel($cartid, $productid, $quantity)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "INSERT INTO cart_item (`cart_id`, `product_id`, `quantity`) VALUES (?,?,?)";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("sii", $cartid, $productid, $quantity);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function removeFromCartModel($cartid, $productid) 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $this->sql = "DELETE FROM `cart_item` WHERE `cart_id` = ? AND `product_id` = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("si", $cartid, $productid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function checkoutModel($cartid)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $this->sql = "UPDATE `cart` SET `checked_out` = 1 WHERE `id` = ? AND `checked_out` = 0;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $cartid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function deleteCurrentCart($userid)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "DELETE FROM `cart` WHERE `user_id` = ? AND checked_out = 0;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $userid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function transferCart($guestid, $cartid, $userid)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "UPDATE `cart` SET `user_id` = ? WHERE user_id = ? AND id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("sss", $userid, $guestid, $cartid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }
}
?>