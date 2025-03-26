<?php

    include 'handleSMTPs.php';
    include 'db_connect.php';

    $user_id = $_GET['user'];
    $book= $_GET['book'];

    $stmt=$conn->prepare("SELECT * FROM pending_requests WHERE user_id=? AND book=?");
    $stmt->bind_param("ss", $user_id, $book);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $time = new DateTime();
    $time_borrowed = $time->getTimestamp();
    $time->modify("+5 minutes");
    $due_date = $time->getTimestamp();
    $sent = "false";
    $borr = "true";

    $stmt = $conn->prepare("INSERT INTO borrowed_books (title, author, borrower, time_borrowed, due_date, email_sent) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("sssiis", $book, $row['author'], $user_id, $time_borrowed, $due_date, $sent);
    if ($stmt->execute()) {
        $stmt = $conn->prepare("DELETE FROM pending_requests where user_id=? AND book=?");
        $stmt->bind_param("ss", $user_id, $book);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE books SET borrowed=? WHERE title=? AND author=?");
        $stmt->bind_param("sss", $borr, $book, $row['author']);
        $stmt->execute();

        $sub = "Admin approval";
        $cntnt = "Your request to borrow $book by {$row['author']} has been approved by admins.";
        $red = 'admin_dash.php';
        email($row['email'], $sub, $cntnt, $red);
    } else {
        echo "Something went wrong";
    }

?>