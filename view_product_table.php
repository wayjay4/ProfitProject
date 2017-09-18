
<div class="container-fluid">
  <main role="main">
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

        <?php foreach($this->product_data as $key=>$product) : ?>
              <tr>
                <td><?=$product->__getSku()?></td>
                <td><?=money_format('$%i', $product->__getCost())?></td>
                <td><?=money_format('$%i', $product->__getPrice())?></td>
                <td><?=$product->__getQty()?></td>
                <td><span class="<?php if($product->__getProfitMargin() >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=$product->__getProfitMargin()?></span></td>
                <td><span class="<?php if($product->__getProfitUSD() >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=money_format('$%i', $product->__getProfitUSD())?></span></td>
                <td><span class="<?php if($product->__getProfitCAD() >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=money_format('$%i', $product->__getProfitCAD())?></span></td>
              </tr>
        <?php endforeach; ?>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><strong>Average Price:<br /><?=money_format('$%i', $this->average_price)?></strong></td>
            <td><strong>Total Qty:<br /><?=$this->total_qty?></strong></td>
            <td><strong>Average Profit Margin:<br /><span class="<?php if($this->average_profit_margin >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=$this->average_profit_margin?></span></strong></td>
            <td><strong>Total Profit (USD):<br /><span class="<?php if($this->total_profitUSD >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=money_format('$%i', $this->total_profitUSD)?></span></strong></td>
            <td><strong>Total Profit (CAD):<br /><span class="<?php if($this->total_profitCAD >= 0) echo("num_pos"); else echo("num_neg"); ?>"><?=money_format('$%i', $this->total_profitCAD)?></span></strong></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</div>
