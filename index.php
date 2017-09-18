<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP Exercise - File Processor">
    <meta name="author" content="Waylon Dixon">

    <title>My Profit Project</title>

    <!-- Custom styles for this template -->
    <link href="css/app.css" rel="stylesheet">
    <link
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
      crossorigin="anonymous">
  </head>

  <body>

    <div class="container-fluid">
      <div class="row">
      <?php
      // display HTML view for uploading files
      require("view_upload_file.php");

      // if upload path is set use it, else use the default csv file
      isset($upload_path) ? $filename = $upload_path : $filename = "products_example.csv";

      // initalize ProductModel to display HTML product table
      require("product_model.php");
      $product_model = new ProductModel($filename);

      ?>
      </div>

    </div><!-- /.container -->

    <script src="/js/app.js"></script>

  </body>
</html>
