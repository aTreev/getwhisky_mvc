<?php
namespace Getwhisky\Model;
use Getwhisky\Model\DatabaseConnection;
use mysqli_sql_exception;

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

        $this->sql = "SELECT id FROM user_addresses WHERE `user_id` = ?;";
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

    public function updateAddressModel($id, $userid, $addressIdentifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "UPDATE `user_addresses` SET 
        `address_name`=?,
        `recipient`=?,
        `mobile_number`=?,
        `postcode`=?,
        `line1`=?,
        `line2`=?,
        `city`=?,
        `county`=?
        WHERE `id` = ? AND `user_id` = ?";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ssssssssss", $addressIdentifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county, $id, $userid);

        try {
            $this->stmt->execute();
            return $this->stmt->affected_rows;

        } catch(mysqli_sql_exception $e) {
            return $e->getMessage();
        }
    }
}
?>