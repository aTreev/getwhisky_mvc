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
    private $discountTotal;

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
    private function setDiscountTotal($discount) { $this->discountTotal = $discount; return $this; }
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
    public function getDiscountTotal() { return $this->discountTotal; }
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


    public function createOrder($id, $paymentIntent, $userid, $total, $discountTotal, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode)
    {
        parent::createorderModel($id, $paymentIntent, $userid, $total, $discountTotal, $deliveryCost, $deliveryRecipient, $deliveryLine1, $deliveryLine2, $deliveryCity, $deliveryCounty, $deliveryPostcode);
        
        $this->setId($id)
        ->setStripePaymentIntent($paymentIntent)
        ->setUserid($userid)
        ->setTotal($total)
        ->setDiscountTotal($discountTotal)
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



    public function initOrder($orderid, $userid)
    {
        $exists = 0;
        $orderData = parent::getUserOrder($orderid, $userid);

        if ($orderData) {
            $data = $orderData[0];
            $exists = 1;

            $this->setId($data['id'])
            ->setStripePaymentIntent($data['stripe_payment_intent'])
            ->setUserid($data['user_id'])
            ->setStatus($data['status_label'])
            ->setTotal(number_format($data['total'], 2, '.', ' '))
            ->setDiscountTotal(number_format($data['discount_total'], 2, '.', ' '))
            ->setDeliveryCost(number_format($data['delivery_cost'], 2, '.', ' '))
            ->setDeliveryRecipient($data['delivery_recipient'])
            ->setDeliveryLine1($data['delivery_line1'])
            ->setDeliveryLine2($data['delivery_line2'])
            ->setDeliveryCity($data['delivery_city'])
            ->setDeliveryCounty($data['delivery_county'])
            ->setDeliveryPostcode($data['delivery_postcode']);

            $this->setItems(parent::getOrderItems($this->getId()));
        }
        return $exists;
    }
}
?>