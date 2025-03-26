<?php

    include 'db_connect.php';  
    include 'handleSMTPs.php';

        // Checks for all the borrowed books for those which have reached the due date to be returned 
        // and sends an smtp to those who borrowed the books
        $temp = "true";
        $temp1 = "false";
        $stmt = $conn->prepare("SELECT * FROM borrowed_books WHERE email_sent=?");
        $stmt->bind_param('s', $temp1);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $title = $row['title'];
            $current_time = new DateTime();
            $due_time = new DateTime();
            $due_time->setTimestamp($row['time_limit']);
    
            if ($current_time >= $due_time) {
                $stmt = $conn->prepare('SELECT * FROM users where user_id=?');
                $stmt->bind_param('s', $row['borrower']);
                $stmt->execute();
    
                $result2 = $stmt->get_result();
                $row2 = $result2->fetch_assoc();
                

                $stmt = $conn->prepare("UPDATE borrowed_books SET email_sent=? WHERE title=? AND author=?");
                $stmt->bind_param("sss", $temp, $row['title'], $row['author']);
                $stmt->execute();
                

                $sub = "Warning";
                $cntnt = "Your account will be deactivated if you do not return the book which you have borrowed
                    <br>
                    <b>Title: </b> $title";
                $red = "";
                email($row2['email'], $sub, $cntnt, $red);
            }
        }

?>