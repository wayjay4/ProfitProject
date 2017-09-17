<?php

class Product
{
  // local vars
  public $sku;
  public $price;
  public $qty;
  public $cost;
  public $p_margin;
  public $profit_usd;
  public $profit_cad;

  public function __construct($sku, $price, $qty, $cost, $p_margin, $profit_usd, $profit_cad)
  {
    // set local vars
    $this->sku = $sku;
    $this->price = $price;
    $this->qty = $qty;
    $this->cost = $cost;
    $this->p_margin = $p_margin;
    $this->profit_usd = $profit_usd;
    $this->profit_cad = $profit_cad;
  }

  public function __getSku()
  {
    return $this->sku;
  }

  public function __getPrice()
  {
    return $this->price;
    //return money_format('$%i', $this->price);
  }

  public function __getQty()
  {
    return $this->qty;
  }

  public function __getCost()
  {
    return $this->cost;
    //return money_format('$%i', $this->cost);
  }

  public function __getProfitMargin()
  {
    return $this->p_margin;
  }

  public function __getProfitUSD()
  {
    return $this->profit_usd;
    //return money_format('$%i', $this->profit_usd);
  }

  public function __getProfitCAD()
  {
    return $this->profit_cad;
    //return money_format('$%i', $this->profit_cad);
  }
}
?>
