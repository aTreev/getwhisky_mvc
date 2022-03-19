<?php
namespace Getwhisky\Model;
use Getwhisky\Model\DatabaseConnection;

class AddressModel
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    public function __construct()
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }

    public function getUserAddressIdsModel($userid, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT id FROM user_addresses WHERE userid = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $userid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }

    public function getUserAddressById($id, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM user_addresses WHERE id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $id);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }
}
?>