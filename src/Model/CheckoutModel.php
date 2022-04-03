<?php
namespace Getwhisky\Model;

use Getwhisky\Model\DatabaseConnection;

class CheckoutModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    public function getDeliveryOptionsModel($style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM delivery_options;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }
}
?>