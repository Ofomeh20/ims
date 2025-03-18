<?php

session_start();
include 'db_connect.php';


$book_id = $_GET['id'];
$temp = "false";
$borrower = null;
$cleared_time = null;

// Updates the database to remove the person who borrowed the book
// Clears the due date for the book to be returned
//  
$stmt = $conn->prepare("UPDATE books SET borrowed=?, borrower=?, time_limit=?, email_sent=? where id=?");
$stmt->bind_param("ssisi", $temp, $borrower, $cleared_time, $temp, $book_id);

if ($stmt->execute()){
    header('Location: index.php');
  }else{
    echo 'error';
  }


?>

