<?php

require("product.php");

class ProductModel
{
  // local vars
  public $sku_col = array();
  public $price_col = array();
  public $qty_col = array();
  public $cost_col = array();
  public $product_data = array();
  public $conv_rate;
  public $row_count;
  public $average_price;
  public $total_qty;
  public $average_profit_margin;
  public $total_profitUSD;
  public $total_profitCAD;

  public function __construct($filename)
  {
    //set class vars
    $this->row_count = 0;
    $this->average_price = 0;
    $this->total_qty = 0;
    $this->average_profit_margin = 0;
    $this->total_profitUSD = 0;
    $this->total_profitCAD = 0;
    $this->extractConversionRate($web_file = "http://quote.yahoo.com/d/quotes.csv?s=USDCAD=X&f=l1&e=.csv"); // canadian exchange rate
    $this->extractProductData($filename);
    $this->calculateTotalsAndAverages();

    // display html product data
    //$this->displayRAWProductData();
    $this->displayHTMLProductData();
  }

  protected function extractConversionRate($webfile)
  {
    // local vars
    $handle;
    $conversion_data = array();

    // if file exists, the  n open and read contents
    if (($handle = fopen($webfile, "r")) !== FALSE) :
      // read each line and save data until end of file is reached
      while(!feof($handle)) :
        // get data in file, then save it
        $conversion_data = fgetcsv($handle);
      endwhile;

      fclose($handle);
    endif;

    // set class var
    $this->conv_rate = $conversion_data[0];
  }

  protected function extractProductData($filename)
  {
    // local vars
    $handle;
    $row;
    $data_arr = array();

    // if file exists, the  n open and read contents
    if (($handle = fopen($filename, "r")) !== FALSE) :
      // set row counter
      $row = 0;

      // read each line and save data until end of file is reached
      while(!feof($handle)) :
        // if data in line is available, then save it and incrment row
        if($data_arr = fgetcsv($handle)) :

          // if we are in the header row, then set class column names
          // else, set product data
          if($row == 0) :
            $this->setHeaderNames($data_arr);
          elseif($row > 0) :
            $this->addProductData($data_arr);
          endif;

          // increment row counter
          $row++;
        endif;
      endwhile;

      fclose($handle);
    endif;
  }

  protected function setHeaderNames($header_arr)
  {
    // set class column names
    foreach($header_arr as $column_id => $field_name) :
      if(trim($field_name) == "sku") :
        //$this->sku_col = $column_id;
        $this->sku_col = array("fieldname"=>trim($field_name), "colnum"=>$column_id);
      elseif(trim($field_name) == "price") :
        //$this->price_col = $column_id;
        $this->price_col = array("fieldname"=>trim($field_name), "colnum"=>$column_id);
      elseif(trim($field_name) == "qty") :
        //$this->qty_col = $column_id;
        $this->qty_col = array("fieldname"=>trim($field_name), "colnum"=>$column_id);
      elseif(trim($field_name) == "cost") :
        //$this->cost_col = $column_id;
        $this->cost_col = array("fieldname"=>trim($field_name), "colnum"=>$column_id);
      endif;
    endforeach;
  }

  protected function addProductData($product_arr)
  {
    // calculate revenue, cost, profit, and profit margin
    $total_revenue = $product_arr[$this->price_col["colnum"]] * $product_arr[$this->qty_col["colnum"]];
    $total_cost = $product_arr[$this->cost_col["colnum"]] * $product_arr[$this->qty_col["colnum"]];
    $profit_usd = $total_revenue - $total_cost;
    $p_margin = $profit_usd / $total_revenue;

    // convert profit from USD to CAD
    $profit_cad = $profit_usd * $this->conv_rate;

    // set class product data array and increment row count
    $this->product_data[++$this->row_count] =
      new Product(
        $product_arr[$this->sku_col["colnum"]],
        $product_arr[$this->price_col["colnum"]],
        $product_arr[$this->qty_col["colnum"]],
        $product_arr[$this->cost_col["colnum"]],
        $p_margin,
        $profit_usd,
        $profit_cad
      );
  }

  protected function calculateTotalsAndAverages()
  {
    // calculate and set totals for price(avg), qty, profit margin(avg), proftiUSD, and profitCAD
    foreach($this->product_data as $key=>$product) :
      $this->average_price += $product->__getPrice();
      $this->total_qty += $product->__getQty();
      $this->average_profit_margin += $product->__getProfitMargin();
      $this->total_profitUSD += $product->__getProfitUSD();
      $this->total_profitCAD += $product->__getProfitCAD();
    endforeach;

    // calculate and set averages for price and profit margin
    $this->average_price /= $this->row_count;
    $this->average_profit_margin /= $this->row_count;
  }

  public function displayRAWProductData()
  {
    // display Product data
    echo("<p>HEADER DATA:</p>");
    echo("<p>Sku Column ID: ".$this->sku_col."</p>");
    echo("<p>Price Column ID: ".$this->price_col."</p>");
    echo("<p>Quantity Column ID: ".$this->qty_col."</p>");
    echo("<p>Cost Column ID: ".$this->cost_col."</p>");

    echo("<p>CONVERSION RATE:</p>");
    echo '<pre>';
      print_r($this->conv_rate);
    echo '</pre>';

    echo("<p>ROW COUNT:</p>");
    echo '<pre>';
      print_r($this->row_count);
    echo '</pre>';

    echo("<p>PRODUCT DATA:</p>");
    echo '<pre>';
      print_r($this->product_data);
    echo '</pre>';

    // display results
    echo("<p>Average Price: ".$this->average_price."</p>");
    echo("<p>Total Qty: ".$this->total_qty."</p>");
    echo("<p>Average P.Margin: ".$this->average_profit_margin."</p>");
    echo("<p>Total Profit USD: ".$this->total_profitUSD."</p>");
    echo("<p>Total Profit CAD: ".$this->total_profitCAD."</p>");
  }

  public function displayHTMLProductData()
  {
    // display Product data
    echo("<h1>HEADER DATA:</h1>");
    echo("<p>Sku Column ID: ".$this->sku_col["colnum"]."</p>");
    echo("<p>Price Column ID: ".$this->price_col["colnum"]."</p>");
    echo("<p>Quantity Column ID: ".$this->qty_col["colnum"]."</p>");
    echo("<p>Cost Column ID: ".$this->cost_col["colnum"]."</p>");

    echo("<h1>PRODUCT DATA:</h1>");
    foreach($this->product_data as $key=>$product) :
      // do stuff
      echo("<p>SKU: ".$product->__getSku()."</p>");
      echo("<p>Cost: ".$product->__getCost()."</p>");
      echo("<p>Price: ".$product->__getPrice()."</p>");
      echo("<p>QTY: ".$product->__getQty()."</p>");
      echo("<p>Profit Margin: ".$product->__getProfitMargin()."</p>");
      echo("<p>Total Profit (USD): ".$product->__getProfitUSD()."</p>");
      echo("<p>Total Profit (CAD): ".$product->__getProfitCAD()."</p>");
    endforeach;

    // display results
    echo("<h1>PRODUCT TOTALS AND AVERAGES:</h1>");
    echo("<p>Average Price: ".$this->average_price."</p>");
    echo("<p>Total Qty: ".$this->total_qty."</p>");
    echo("<p>Average P.Margin: ".$this->average_profit_margin."</p>");
    echo("<p>Total Profit USD: ".$this->total_profitUSD."</p>");
    echo("<p>Total Profit CAD: ".$this->total_profitCAD."</p>");
  }
}
?>
