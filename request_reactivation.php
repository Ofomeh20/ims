<?php

session_start();
include 'db_connect.php';
include 'handleSMTPs.php';

$account_no = $_GET['account_no'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();


$stmt = $conn->prepare("INSERT INTO pending_reactivation_requests (username, email, user_id, account_no) VALUES (?,?,?,?)");
$stmt->bind_param("sssi", $user['username'], $user['email'], $_SESSION['user_id'], $account_no);
if ($stmt->execute()){
    $sub = "Reactivation request";
    $cntnt = "Your request to reactivate your account is being processed by the admins";
    $red = "index.php";
    email($user['email'], $sub, $cntnt, $red);
  }else{
    echo 'error';
  }


?>

