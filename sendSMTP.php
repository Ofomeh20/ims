<?php

    include 'db_connect.php';
    require 'PHPMailer-PHPMailer-1a06bd3/src/PHPMailer.php';
    require 'PHPMailer-PHPMailer-1a06bd3/src/SMTP.php';
    require 'PHPMailer-PHPMailer-1a06bd3/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

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
    
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'athekamebash@gmail.com';  // Your email
                    $mail->Password = 'zudqcyoqdgotmewz';  // Your email app password
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;
    
                    $mail->setFrom('athekamebash@gmail.com', 'Mee-rooms');
                    $mail->addAddress($row2['email']);
    
                    $mail->isHTML(true);
                    $mail->Subject = 'Warning';
                    $mail->Body = "Your account will be deactivated if you do not return the book which you have borrowed
                    <br>
                    <b>Title: </b> $title
                    ";
    
                    $mail->send();

                    $stmt = $conn->prepare('UPDATE books SET email_sent=? where title=?');
                    $stmt->bind_param('ss', $temp, $title);
                    $stmt->execute();

                } catch (Exception $e) {
                    $errors[] = "Message could not be sent.Try again";
                    var_dump($errors);
                    file_put_contents('C:/xampp/htdocs/log.txt', $e->getMessage(), FILE_APPEND);
                    exit();
                }
            }
        }

?>