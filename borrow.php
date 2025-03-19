<?php

session_start();
include 'db_connect.php';

$book_id = $_GET['id'];
$borrowed = "true";
$borrower = $_SESSION['user_id']; 


$stmt = $conn->prepare("INSERT INTO pending_requests (username, email, book, author, price, borrowed, user_id) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssii", $, $borrower, $time_limit, $book_id);
$stmt->execute();

if ($stmt->execute()){
    header('Location: index.php');
  }else{
    echo 'error';
  }


?>

