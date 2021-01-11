<?php session_start();
include "config.php";?>
<?php include "db.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>ID Card Generator</title>
  <!-- MDB icon 
  <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">-->
  <!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/style.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/all.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/cropper.css">
 
</head>
<body>
 <!--Main Navigation-->
 <div class="main-wrapper">
<header>
	<nav class="navbar  navbar-expand-lg navbar-dark pink scrolling-navbar">   
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo BASE_URL;?>/index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <?php
       if(isset($_SESSION['userid'])){?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL;?>/templates.php">Templates</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL;?>/choose_templates.php">Template Configuration</a>
        </li>
          <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL;?>/save_template.php">Layout Setup</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL;?>/import_users.php">Import Data</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL;?>/history.php">History</a>
        </li> 
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo BASE_URL;?>/logout.php">Logout</a>
        </li> 
        <?php }?>    
      </ul>
    </div>
  </nav>

</header>
<!--Main Navigation-->