
<main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
  <h1>Profit Project</h1>

  <h2>Product Data</h2>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>SKU</th>
          <th>Cost</th>
          <th>Price</th>
          <th>QTY</th>
          <th>Profit Margin</th>
          <th>Total Profit (USD)</th>
          <th>Total Profit (CAD)</th>
        </tr>
      </thead>
      <tbody>

      <?php if($this->row_count > 0) : ?>
        <?php foreach($this->product_data as $key=>$product) : ?>
              <tr>
                <td><?=$product->__getSku()?></td>
                <td><?=money_format('$%i', $product->__getCost())?></td>
                <td><?=money_format('$%i', $product->__getPrice())?></td>
                <td><span class="<?=getColorClassName($product->__getQty())?>"><?=$product->__getQty()?></span></td>
                <td><span class="<?=getColorClassName($product->__getProfitMargin())?>"><?=$product->__getProfitMargin()?></span></td>
                <td><span class="<?=getColorClassName($product->__getProfitUSD())?>"><?=money_format('$%i', $product->__getProfitUSD())?></span></td>
                <td><span class="<?=getColorClassName($product->__getProfitCAD())?>"><?=money_format('$%i', $product->__getProfitCAD())?></span></td>
              </tr>
        <?php endforeach; ?>
      <?php else :?>
              <tr>
                <td colspan="7"><div class="alert alert-warning" role="alert">There was no data found in the file '<?php echo($this->filename); ?>' to display.</div></td>
              </tr>
      <?php endif; ?>

        <tr class="footer">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>Average Price:<br /><?=money_format('$%i', $this->average_price)?></td>
          <td>Total Qty:<br /><span class="<?=getColorClassName($this->total_qty)?>"><?=$this->total_qty?></span></td>
          <td>Average Profit Margin:<br /><span class="<?=getColorClassName($this->average_profit_margin)?>"><?=$this->average_profit_margin?></span></td>
          <td>Total Profit (USD):<br /><span class="<?=getColorClassName($this->total_profitUSD)?>"><?=money_format('$%i', $this->total_profitUSD)?></span></td>
          <td>Total Profit (CAD):<br /><span class="<?=getColorClassName($this->total_profitCAD)?>"><?=money_format('$%i', $this->total_profitCAD)?></span></td>
        </tr>
      </tbody>
    </table>
  </div>
</main>

<?php
// local function
function getColorClassName($value)
{
  return $value >= 0 ? $name="num_pos" : $name="num_neg";

}
?>
