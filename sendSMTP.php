<?php

    include 'db_connect.php';  
    include 'handleSMTPs.php';

        $temp = "true";
        $stmt = $conn->prepare("SELECT * FROM books where borrowed=?");
        $stmt->bind_param('s', $temp);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $title = $row['title'];
            $current_time = new DateTime();
            $due_time = new DateTime();
            $due_time->setTimestamp($row['time_limit']);
    
            if ($current_time >= $due_time && $row['email_sent'] != "true") {
                $stmt = $conn->prepare('SELECT * FROM users where user_id=?');
                $stmt->bind_param('s', $row['borrower']);
                $stmt->execute();
    
                $result2 = $stmt->get_result();
                $row2 = $result2->fetch_assoc();
                

                $sub = "Warning";
                $cntnt = "Your account will be deactivated if you do not return the book which you have borrowed
                    <br>
                    <b>Title: </b> $title";
                $red = "";
                email($row2['email'], $sub, $cntnt, $red);
            }
        }

?>