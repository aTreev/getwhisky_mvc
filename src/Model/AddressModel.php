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

    protected function getUserAddressIdsModel($userid, $style=MYSQLI_ASSOC)
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

    protected function getAddressIdsModel($style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT id FROM user_addresses;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }

    protected function getUserAddressById($id, $userid, $style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT * FROM user_addresses WHERE id = ? AND user_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ss", $id, $userid);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }

    protected function updateAddressModel($id, $userid, $addressIdentifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county)
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
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function deleteAddressModel($addressid, $userid)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "DELETE FROM user_addresses WHERE id = ? AND user_id = ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ss", $addressid, $userid);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function addAddressModel($id, $userid, $identifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "INSERT INTO user_addresses (`id`, `user_id`, `address_name`, `recipient`, `mobile_number`, `postcode`, `line1`, `line2`, `city`, `county`) VALUES (?,?,?,?,?,?,?,?,?,?);";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ssssssssss", $id, $userid, $identifier, $recipientName, $mobileNumber, $postcode, $line1, $line2, $city, $county);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }
}
?>