<?php 
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

?>


<!DOCTYPE html>
<html>
  <head>
    <title>Sign up</title>
    <meta name="viewport" content="width=device-width,user-scalable= no">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
  </head>
  <body>
    <h1>
      Welcome to J&B library <?php echo $_SESSION['username']; ?>
      <a href="index.php">List of books</a>
    </h1>
  </body>
</html>