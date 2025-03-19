<?php 

    require 'PHPMailer-PHPMailer-1a06bd3/src/PHPMailer.php';
    require 'PHPMailer-PHPMailer-1a06bd3/src/SMTP.php';
    require 'PHPMailer-PHPMailer-1a06bd3/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function email($receiver, $subject, $body, $redirect) {
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
                $mail->addAddress($receiver);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;

                $mail->send();
                if ($redirect != "") {
                    header('Location: '. $redirect);
                    exit();
                }
            } catch (Exception $e) {
                $errors[] = "Message could not be sent.Try again";
                file_put_contents('C:/xampp/htdocs/log.txt', $e->getMessage(), FILE_APPEND);
                if ($redirect != "") {
                    header('Location: '. $redirect);
                    exit();
                } 
            }
    }

?>