<?php 

    include 'db_connect.php';
    include 'handleSMTPs.php';
    $user_id = $_GET['user'];
    $temp = "suspended";
    $stmt=$conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    

    $stmt=$conn->prepare("UPDATE users SET status=? WHERE user_id=?");
    $stmt->bind_param("ss", $temp, $user_id);
    if ($stmt->execute()){
        $sub = "Account suspended";
        $cntnt = "Your account has been suspended by the admins.";
        $red = 'admin_dash.php';
        email($user['email'], $sub, $cntnt, $red);
    } else {
        echo "problem";
    }

?>