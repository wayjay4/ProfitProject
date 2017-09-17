<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP Exercise - File Processor">
    <meta name="author" content="Waylon Dixon">

    <title>My Profit Project</title>

    <!-- Custom styles for this template -->
    <link href="/css/app.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <?php
      // get includes
      require("product_model.php");

      $product_model = new ProductModel($filename = "products.csv");

      ?>

    </div><!-- /.container -->

    <script src="/js/app.js"></script>

  </body>
</html>
