<?php

  session_start();
  require 'PHPMailer-PHPMailer-1a06bd3/src/PHPMailer.php';
  require 'PHPMailer-PHPMailer-1a06bd3/src/SMTP.php';
  require 'PHPMailer-PHPMailer-1a06bd3/src/Exception.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  $_SESSION['user_id'] = null;
  include 'db_connect.php';
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cnfm_password = $_POST['cnfm_password'];
    $alrd_exists = false;

    $result = $conn->query("SELECT * FROM users");

    while ($row = $result->fetch_assoc()) {
      if ($email == $row['email'] && password_verify($password, $row['password'])){
        $alrd_exists = true;
        break;
      } else {
        $alrd_exists = false;
      }
    }



    if ($password === $cnfm_password){
      if($alrd_exists == false){
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $_SESSION['user_id'] = $email.$password_hash;
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_id) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $username, $email, $password_hash, $_SESSION['user_id']);


        if ($stmt->execute()){
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
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Account creation';
                $mail->Body = "Your account has successfully been created! Welcome to J&B library";

                $mail->send();
                header('Location: index.php'); // Redirect to OTP verification page
                exit();
            } catch (Exception $e) {
                // $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $errors[] = "Message could not be sent.Try again";
                var_dump($errors);
                // Redirect to the edit profile page either way
                header('Location: index.php');
                exit();
            }
        }else{
          $_SESSION['user_id'] = null;
          $error_message = "Registration failed";
        }


      } else {
        $error_message = "Account already exists";
      }
    } else {
      $error_message = "Passwords are not the same";
    }
  }

  
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Sign up</title>
    <meta name="viewport" content="width=device-width,user-scalable= no">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
  </head>
  <body>
    <div class="container py-3">
      <h1 class="text-center">Sign up</h1>

      <!--Displays an error message if there is something wrong with the form input-->
      <?php
        if (isset($error_message)) {
            echo '<div class="alert alert-warning mx-auto col-11 col-md-10 col-lg-8">'.$error_message.'</div>';
            unset($error_message);
        }
      ?>

      <!--SIgn up form-->
      <form method="POST" class="row col-9 mx-auto">
        <label class="my-1">Your username:</label>
        <input type="text" name="username" class="form-control col-9 input">

        <label class="my-1">Your E-mail:</label>
        <input type="email" name="email" class="form-control col-9 input">

        <label class="my-1">Your password:</label>
        <input type="password" name="password" class="form-control col-9 input">

        <label class="my-1">Confirm password:</label>
        <input type="password" name="cnfm_password" class="form-control col-9 input">

        <button type="submit" class="btn btn-success my-4">Submit</button>
      </form>
    </div>
  </body>
</html>