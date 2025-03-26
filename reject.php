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

    $stmt = $conn->prepare("DELETE FROM pending_requests where user_id=? AND book=?");
    $stmt->bind_param("ss", $user_id, $book);
    if ($stmt->execute()) {
        $sub = "Admin approval";
        $cntnt = "Your request to borrow has been approved by admins.";
        $red = 'admin_dash.php';
        email($row['email'], $sub, $cntnt, $red);
    } else {
        echo "Something went wrong";
    }

?>