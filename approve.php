<?php

    include 'handleSMTPs.php';
    include 'db_connect.php';

    $user_id = $_GET['user'];
    $stmt=$conn->prepare("SELECT * FROM pending_accounts WHERE user_id=?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $role = "member";
    $status = "running";

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_id, role, status) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $row['username'], $row['email'], $row['password'], $user_id, $role, $status);

    if ($stmt->execute()) {
        $stmt = $conn->prepare("DELETE FROM pending_accounts where user_id=?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        $sub = "Admin approval";
        $cntnt = "Your account has been approved by admins.";
        $red = 'admin_dash.php';
        email($row['email'], $sub, $cntnt, $red);
    } else {
        echo "Something went wrong";
    }

?>