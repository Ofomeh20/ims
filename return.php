<?php

session_start();
include 'handleSMTPs.php';
include 'db_connect.php';


$user_id = $_GET['user'];
$book = $_GET['book'];
$author = $_GET['author'];
$temp = "false";
$borrower = null;
$cleared_time = null;

$stmt=$conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

$role = "member";
$status = "running";

$stmt = $conn->prepare("UPDATE books SET borrowed=? WHERE title=? AND author=?");
$stmt->bind_param("sss", $temp, $book, $author);
if ($stmt->execute()) {
    $stmt = $conn->prepare("DELETE FROM borrowed_books WHERE borrower=? AND title=? AND author=?");
    $stmt->bind_param("sss", $user_id, $book, $author);
    $stmt->execute();

    $sub = "Book return";
    $cntnt = "The book has been returned successfully";
    $red = 'borrowed.php';
    email($row['email'], $sub, $cntnt, $red);
} else {
    echo "Something went wrong";
}

// Updates the database to remove the person who borrowed the book
// Clears the due date for the book to be returned


?>

