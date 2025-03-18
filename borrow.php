<?php

session_start();
include 'db_connect.php';

$book_id = $_GET['id'];
$borrowed = "true";
$borrower = $_SESSION['user_id'];
$time = new DateTime();
$time->modify('+1 minutes');
$time_limit = $time->getTimestamp();


$stmt = $conn->prepare("UPDATE books SET borrowed=?, borrower=?, time_limit=? where id=?");
$stmt->bind_param("ssii", $borrowed, $borrower, $time_limit, $book_id);
$stmt->execute();

if ($stmt->execute()){
    header('Location: index.php');
  }else{
    echo 'error';
  }


?>

