<?php

class Product
{
  // local vars
  private $sku;
  private $price;
  private $qty;
  private $cost;

  public function __construct($sku, $price, $qty, $cost)
  {
    // set local vars
    $this->sku = $sku;
    $this->price = $price;
    $this->qty = $qty;
    $this->cost = $cost;
  }

  public function __getSku()
  {
    return $this->sku;
  }

  public function __getPrice()
  {
    return $this->price;
  }

  public function __getQty()
  {
    return $this->qty;
  }

  public function __getCost()
  {
    return $this->cost;
  }
}
?>
