
<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
  <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>

<?php
  // local vars
  $upload_path;

  // if a file as uploaded, then validate it and save it
  if(!empty($_FILES['uploaded_file'])) :
    // validate if file is of type: '.csv'
    // if valid, then set upload path var and display success message
    // else, delete invalid file from tmp_dir and display error message
    if($_FILES['uploaded_file']['type'] == "text/csv") :
      echo("<div class='alert alert-success' role='alert'>SUCCESS: The file ".  basename($_FILES['uploaded_file']['name'])." has been successfully uploaded.</div>");
      echo("<div class='alert alert-primary' role='alert'>When ready you can select a new file to upload.</div>");
      $upload_path = $_FILES['uploaded_file']['tmp_name'];
    else :
      echo("<div class='alert alert-danger' role='alert'>ERROR: File upload is invalid, please make sure file is of type '.csv'. Please try again.</div>");
      echo("<div class='alert alert-primary' role='alert'>NOTICE: Sample 'product.csv' file is being displayed instead.</div>");
      unlink($_FILES['uploaded_file']['tmp_name']);
      //$upload_path = "/";
    endif;
  else :
    echo("<div class='alert alert-primary' role='alert'>NOTICE: Sample 'products_example.csv' file is currently displayed. <br />Please select a new file to upload.</div>");
  endif;
?>
</nav>
