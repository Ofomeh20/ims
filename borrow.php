<?php

session_start();
include 'db_connect.php';
include 'handleSMTPs.php';

$book_id = $_GET['id'];
$account_no = $_GET['account_no'];
$borrowed = "true";
$borrower = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
$stmt->bind_param("s", $book_id);
$stmt->execute();
$res = $stmt->get_result();
$book = $res->fetch_assoc();


$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();


$stmt = $conn->prepare("INSERT INTO pending_requests (username, email, book, author, price, account_no, user_id) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("ssssiis", $user['username'], $user['email'], $book['title'], $book['author'], $book['price'], $account_no, $borrower);
if ($stmt->execute()){
    $sub = "Borrow request";
    $cntnt = "Your request to borrow <b> {$book['title']} by {$book['author']} </b> is being processed by the admins";
    $red = "index.php";
    email($user['email'], $sub, $cntnt, $red);
  }else{
    echo 'error';
  }


?>

