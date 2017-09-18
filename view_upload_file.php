
<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
  <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>

  <?php if($this->upload_mssg !== "") echo($this->upload_mssg); ?>
</nav>
