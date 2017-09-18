<?php

require("product.php");

class ProductModel
{
  // local vars
  public $filename;
  public $sku_col;
  public $price_col;
  public $qty_col;
  public $cost_col;
  public $product_data;
  public $conv_rate;
  public $row_count;
  public $average_price;
  public $total_qty;
  public $average_profit_margin;
  public $total_profitUSD;
  public $total_profitCAD;
  public $error_mssg;
  public $upload_mssg;

  public function __construct()
  {
    //set class vars
    $this->row_count = 0;
    $this->average_price = 0;
    $this->total_qty = 0;
    $this->average_profit_margin = 0;
    $this->total_profitUSD = 0;
    $this->total_profitCAD = 0;
    $this->conv_rate = $this->getCADConversionRate($web_file = "http://quote.yahoo.com/d/quotes.csv?s=USDCAD=X&f=l1&e=.csv"); // canadian exchange rate
    $this->error_mssg = "";
    $this->upload_mssg = "";
    $this->filename = "";

    // set file name
    // if a file was uploaded, then get and set file name to uploaded file
    // else use default local file and add default message to upload mssg
    if(!empty($_FILES['uploaded_file'])) :
      $this->filename = $this->getCSVUpload();
    endif;

    // if filename name did not get set, then use the default local file
    // and add default message to upload mssg
    if($this->filename == "") :
      $this->filename = "products_example.csv";
      $this->upload_mssg .= "<div class='alert alert-primary' role='alert'>NOTICE: Sample file 'products_example.csv' is currently displayed. <br />Please select a new file to upload.</div>";
    endif;

    // display HTML for uploading files
    $this->displayHTMLUploadForm();

    // if extract data from file is a success, then complete calculations and display the HTML table
    // else, display an error message
    if($this->extractProductData($this->filename)) :
      // complete calculations
      $this->calculateTotalsAndAverages();
      // display HTML table
      $this->displayHTMLProductData();
    else :
      require("view_error_message.php");
    endif;
  }

  public function getCSVUpload()
  {
    // local vars
    $upload_path;

    // initialize upload path to empty string
    $upload_path = "";

    // validate if file is of type: '.csv'
    // if valid, then set upload path var, add 'success' to upload message, and set result to true
    // else, delete invalid file from tmp_dir and add 'error' to upload message
    if(pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION) == "csv") :
      // set filename
      $upload_path = $_FILES['uploaded_file']['tmp_name'];
      // add success message to upload mssg
      $this->upload_mssg .= "<div class='alert alert-success' role='alert'>SUCCESS: The file ".basename($_FILES['uploaded_file']['name'])." has been successfully uploaded.</div>"
                           ."<div class='alert alert-primary' role='alert'>When ready you can select a new file to upload.</div>";
    else :
      // delete invalid file from temp dir
      unlink($_FILES['uploaded_file']['tmp_name']);
      // add error message to upload mssg
      $this->upload_mssg .= "<div class='alert alert-danger' role='alert'>ERROR: File upload is invalid, please make sure file is of type '.csv'. Please try again.</div>"
                           ."<div class='alert alert-warning' role='alert'>NOTICE: Sample file 'products_example.csv' is being displayed instead.</div>";
    endif;

    // return result
    return $upload_path;
  }

  public function getCADConversionRate($webfile)
  {
    // local vars
    $handle;
    $conversion_rate = array();

    // if file exists, then open and read contents
    if (($handle = fopen($webfile, "r")) !== FALSE) :
      // read each line and save data until end of file is reached
      while(!feof($handle)) :
        // get data in file, then save it
        $conversion_rate = fgetcsv($handle);
      endwhile;

      fclose($handle);
    endif;

    // return conversion rate
    return $conversion_rate[0];
  }

  protected function extractProductData($filename)
  {
    // local vars
    $handle;
    $row;
    $data_arr = array();
    $result;

    // if file exists, then read contents and save product data
    // else, display error message and exit
    if (($handle = fopen($filename, "r")) !== FALSE) :
      // set row counter
      $row = 0;

      // read each line and save data until end of file is reached
      while(!feof($handle)) :
        // if data in line is available, then save it and incrment row
        if($data_arr = fgetcsv($handle)) :

          // if we are in the header row, then set class column names/numbers
          // else, add row to product data array
          if($row == 0) :
            // if set header names is successful, then set result to true
            // else, set error message and return false
            if($this->setHeaderNames($data_arr)) :
              // set result
              $result = true;
            else :
              $result = false;
              $this->error_mssg .= "
                  <h3>ERROR ALERT:</h3>
                  <p>
                    The file must contain the following headers: 'sku', 'price', 'qty', and 'cost'.
                    Please check your file and try again.
                  </p>";

              // return result
              return $result;
            endif;
          elseif($row > 0) :
            // if add product data was successful, then se result to true
            // else, set error message and return false
            if($this->addProductData($data_arr)) :
              // set result
              $result = true;
            else :
              $result = false;
              $this->error_mssg .= "
                  <h3>ERROR ALERT:</h3>
                  <p>
                    The following columns: price', 'qty', and 'cost' must be numeric values.
                    A non-numeric value was found in row '$row' in one of the listed columns in the file.
                    Please check your file and try again.
                  </p>";

              // return result
              return $result;
            endif;
          endif;

          // increment row counter
          $row++;
        endif;
      endwhile;

      fclose($handle);

      // set result to true
      $result = true;
    else :
      // set result to false and set error message
      $result = false;
      $this->error_mssg .= "
          <h3>ERROR ALERT:</h3>
          <p>Could not open file '$this->filename'. Please verify the file exists and try again.</p>";
    endif;

    // return result
    return $result;
  }

  protected function setHeaderNames($header_arr)
  {
    // local vars
    $result;

    // set class column names and numbers
    foreach($header_arr as $column_id => $field_name) :
      if(strtolower(trim($field_name)) === "sku") :
        $this->sku_col = array("fieldname"=>strtolower(trim($field_name)), "colnum"=>$column_id);
      elseif(strtolower(trim($field_name)) === "price") :
        $this->price_col = array("fieldname"=>strtolower(trim($field_name)), "colnum"=>$column_id);
      elseif(strtolower(trim($field_name)) === "qty") :
        $this->qty_col = array("fieldname"=>strtolower(trim($field_name)), "colnum"=>$column_id);
      elseif(strtolower(trim($field_name)) === "cost") :
        $this->cost_col = array("fieldname"=>strtolower(trim($field_name)), "colnum"=>$column_id);
      endif;
    endforeach;

    // verify if all headers have been set, then set result to true
    // else, set result to false and set error message
    if(isset($this->sku_col) && isset($this->price_col) && isset($this->qty_col) && isset($this->cost_col)) :
      $result = true;
    else :
      $result = false;
    endif;

    // return result
    return $result;
  }

  protected function addProductData($product_arr)
  {
    // local vars
    $result;

    // verify that the following have numeric values: price, qty, and cost
    // if they are, then set result to true and complete calculations
    // else, set and return result to false
    if(is_numeric($product_arr[$this->price_col["colnum"]]) &&
       is_numeric($product_arr[$this->qty_col["colnum"]]) &&
       is_numeric($product_arr[$this->cost_col["colnum"]])) :
      // set result
      $result = true;

      // calculate revenue, cost, profit, and profit margin
      $total_revenue = $product_arr[$this->price_col["colnum"]] * $product_arr[$this->qty_col["colnum"]];
      $total_cost = $product_arr[$this->cost_col["colnum"]] * $product_arr[$this->qty_col["colnum"]];
      $profit_usd = $total_revenue - $total_cost;
      $p_margin = $profit_usd / $total_revenue;

      // convert profit from USD to CAD and save
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
    else :
      $result = false;
    endif;

    // return result
    return $result;
  }

  protected function calculateTotalsAndAverages()
  {
    // if there is at least one row, then perform calculations
    if($this->row_count >= 1) :
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
    endif;
  }

  public function displayRAWProductData()
  {
    // display raw Product data
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

    echo("<p>Average Price: ".$this->average_price."</p>");
    echo("<p>Total Qty: ".$this->total_qty."</p>");
    echo("<p>Average P.Margin: ".$this->average_profit_margin."</p>");
    echo("<p>Total Profit USD: ".$this->total_profitUSD."</p>");
    echo("<p>Total Profit CAD: ".$this->total_profitCAD."</p>");
  }

  public function displayHTMLUploadForm()
  {
      // display HTML for uploading files
      require("view_upload_file.php");
  }

  public function displayHTMLProductData()
  {
    // display HTML table of product figures
    require_once("view_product_table.php");
  }
}
?>
