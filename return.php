<?php

session_start();
include 'db_connect.php';

$book_id = $_GET['id'];
$borrowed = "false";
$borrower = null;
$stmt = $conn->prepare("UPDATE books SET borrowed=?, borrower=? where id=?");
$stmt->bind_param("ssi", $borrowed, $borrower, $book_id);
$stmt->execute();

if ($stmt->execute()){
    header('Location: index.php');
  }else{
    echo 'error';
  }


?>

