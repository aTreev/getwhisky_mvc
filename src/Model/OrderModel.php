<?php
namespace Getwhisky\Model;

class OrderModel {
    private $sql;
    private $stmt;
    private static  $DatabaseConnection;

    public function __construct()
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getOrderIdsModel($style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT id FROM orders;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }

    protected function createorderModel($id, $paymentIntent, $userid, $total, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $processingStatus = 1;
        $this->sql = 
        "INSERT INTO orders (
            `id`, 
            `stripe_payment_intent`, 
            `user_id`, 
            `status_id`, 
            `total`, 
            `delivery_cost`, 
            `delivery_recipient`, 
            `delivery_line1`, 
            `delivery_line2`, 
            `delivery_city`, 
            `delivery_county`, 
            `delivery_postcode`
        )
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("sssiddssssss", $id, $paymentIntent, $userid, $processingStatus, $total, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }

    protected function addItemToOrderModel($orderid, $productid, $productName, $productImage, $pricePaid, $quantity)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "INSERT INTO `order_items` (`order_id`, `product_id`, `product_name`, `product_image`, `price_paid`, `quantity`) VALUES (?,?,?,?,?,?)";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("ssssdi", $orderid, $productid, $productName, $productImage, $pricePaid, $quantity);
        $this->stmt->execute();
        return $this->stmt->affected_rows;
    }
}
?>