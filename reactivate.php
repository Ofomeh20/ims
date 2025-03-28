<?php 

    include 'db_connect.php';
    include 'handleSMTPs.php';
    $user_id = $_GET['user'];
    $temp = "running";
    $stmt=$conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    

    $stmt=$conn->prepare("UPDATE users SET status=? WHERE user_id=?");
    $stmt->bind_param("ss", $temp, $user_id);
    if ($stmt->execute()){
        $stmt = $conn->prepare("DELETE FROM pending_reactivation_requests WHERE user_id=?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        $sub = "Account reactivated";
        $cntnt = "Your account has been reactivated by the admins.";
        $red = 'admin_dash.php';
        email($user['email'], $sub, $cntnt, $red);
    } else {
        echo "problem";
    }

?>