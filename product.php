<?php

class Product
{
  // local vars
  private $sku;
  private $price;
  private $qty;
  private $cost;
  private $profit;
  private $p_margin;

  public function __construct($sku, $price, $qty, $cost)
  {
    // set local vars
    $this->sku = $sku;
    $this->price = $price;
    $this->qty = $qty;
    $this->cost = $cost;

    // calculate revenue, profit, and profit margin
    $total_revenue = $this->price * $this->qty;
    $total_cost = $this->cost * $this->qty;
    $this->profit = $total_revenue - $total_cost;
    $this->p_margin = $this->profit / $total_revenue;

    // convert profit from USD to CAD
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

  public function __getProfit()
  {
    return $this->profit;
  }

  public function __getProfitMargin()
  {
    return $this->p_margin;
  }
}
?>
