<?php
namespace Getwhisky\Controllers;

use Getwhisky\Model\OrderModel;

class OrderController extends OrderModel
{
    private $id;
    private $stripePaymentIntent;
    private $userid;
    private $status;
    private $total;

    private $deliveryCost;
    private $deliveryRecipient;
    private $deliveryLine1;
    private $deliveryLine2;
    private $deliveryCity;
    private $deliveryCounty;
    private $deliveryPostcode;

    private $items = array();

    private $orderview;

    private function setId($id) { $this->id = $id; return $this;}
    private function setStripePaymentIntent($paymentIntent) { $this->stripePaymentIntent = $paymentIntent; return $this; }
    private function setUserid($userid) { $this->userid = $userid; return $this; }
    private function setStatus($status) { $this->status = $status; return $this; }
    private function setTotal($total) { $this->total = $total; return $this; }
    private function setDeliveryCost($cost) { $this->deliveryCost = $cost; return $this; }
    private function setDeliveryRecipient($recipient) { $this->deliveryRecipient = $recipient; return $this; }
    private function setDeliveryLine1($line1) { $this->deliveryLine1 = $line1; return $this; }
    private function setDeliveryLine2($line2) { $this->deliveryLine2 = $line2; return $this; }
    private function setDeliveryCity($city) { $this->deliveryCity = $city; return $this; }
    private function setDeliveryCounty($county) { $this->deliveryCounty = $county; return $this; }
    private function setDeliveryPostcode($postcode) { $this->deliveryPostcode = $postcode; return $this; }
    private function setItems($items) { $this->items = $items; }

    public function getId() { return $this->id; }
    public function getStripePaymentIntent() { return $this->stripePaymentIntent; }
    public function getUserid() { return $this->userid; }
    public function getStatus() { return $this->status; }
    public function getTotal() { return $this->total; }
    public function getDeliveryCost() { return $this->deliveryCost; }
    public function getDeliveryRecipient() { return $this->deliveryRecipient; }
    public function getDeliveryLine1() { return $this->deliveryLine1; }
    public function getDeliveryLine2() { return $this->deliveryLine2; }
    public function getDeliveryCity() { return $this->deliveryCity; }
    public function getDeliveryCounty() { return $this->deliveryCounty; }
    public function getDeliveryPostcode() { return $this->deliveryPostcode; }
    public function getItems() { return $this->items; }
    public function getView() { return $this->orderview = new OrderView(); }

    // Retrieves all order ids from the database
    public function getOrderIds()
    {
        return parent::getOrderIdsModel();
    }


    public function createOrder($id, $paymentIntent, $userid, $total, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode)
    {
        parent::createorderModel($id, $paymentIntent, $userid, $total, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode);
        
        $this->setId($id)
        ->setStripePaymentIntent($paymentIntent)
        ->setUserid($userid)
        ->setTotal($total)
        ->setDeliveryCost($deliveryCost)
        ->setDeliveryRecipient($deliveryRecipient)
        ->setDeliveryLine1($deliveryLine1)
        ->setDeliveryLine2($deliveryLine2)
        ->setDeliveryCity($deliveryCity)
        ->setDeliveryCounty($deliveryCounty)
        ->setDeliveryPostcode($deliveryPostcode);
    }


    public function addItemToOrder($productid, $productName, $productImage, $pricePaid, $quantity)
    {
        return parent::addItemToOrderModel($this->getId(), $productid, $productName, $productImage, $pricePaid, $quantity);
    }
}
?>