<?php

require("product.php");

class ProductModel
{
  // local vars
  public $sku_col;
  public $price_col;
  public $qty_col;
  public $cost_col;
  public $product_data = array();
  public $row_count;
  public $conv_rate;

  public function __construct($filename)
  {
    //set class vars
    $this->row_count = 0;
    $this->extractProductData($filename);
    $this->extractConversionRate($web_file = "http://quote.yahoo.com/d/quotes.csv?s=USDCAD=X&f=l1&e=.csv");
    $this->displayProductData();
  }

  public function extractConversionRate($webfile)
  {
    // local vars
    $handle;
    $conversion_data = array();

    // if file exists, the  n open and read contents
    if (($handle = fopen($webfile, "r")) !== FALSE) :
      // read each line and save data until end of file is reached
      while(!feof($handle)) :
        // get data in file, then save it
        $conversion_data = fgetcsv($handle));
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
        $this->sku_col = $column_id;

      elseif(trim($field_name) == "price") :
        $this->price_col = $column_id;

      elseif(trim($field_name) == "qty") :
        $this->qty_col = $column_id;

      elseif(trim($field_name) == "cost") :
        $this->cost_col = $column_id;
      endif;
    endforeach;
  }

  protected function addProductData($product_arr)
  {
    // set class product data array and increment row count
    $this->product_data[++$this->row_count] =
      new Product(
        $product_arr[$this->sku_col],
        $product_arr[$this->price_col],
        $product_arr[$this->qty_col],
        $product_arr[$this->cost_col]);
  }

  public function displayProductData()
  {
    // display Product data
    echo("<p>HEADER DATA:</p>");
    echo("<p>Sku Column ID: ".$this->sku_col."</p>");
    echo("<p>Price Column ID: ".$this->price_col."</p>");
    echo("<p>Quantity Column ID: ".$this->qty_col."</p>");
    echo("<p>Cost Column ID: ".$this->cost_col."</p>");

    echo("<p>ROW COUNT:</p>");
    echo '<pre>';
      print_r($this->row_count);
    echo '</pre>';

    echo("<p>PRODUCT DATA:</p>");
    echo '<pre>';
      print_r($this->product_data);
    echo '</pre>';

    echo("<p>CONVERSION RATE:</p>");
    echo '<pre>';
      print_r($this->conv_rate);
    echo '</pre>';
  }
}
?>
