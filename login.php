

<?php

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['email'].$row['password'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['status'] = $row['status'];

        if ($row['role'] == "admin") {
          header('Location: admin_dash.php');
          exit();
        } else {
          header('Location: index.php');
          exit();
        }
        
    } else {
        $error_message = "Invalid credentials!";
    }
    
    $conn->close();
}



?>


<!DOCTYPE html>
<html>
  <head>
    <title>Login to LMS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale= 1.0" />
    <!--BOOTSTRAP CDN-->
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!--CSS-->
    <link href="login.css" rel="stylesheet" />
    <style>
      .password-toggle {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
      }
    </style>
  </head>
  <body>
    <div class="container mt-4">
      <div class="card p-4">
        <h4 class="text-center mb-">Login</h4>

        <?php
        if (isset($error_message)) {
            echo '<div class="alert alert-warning mx-auto col-11 col-md-10 col-lg-8">'.$error_message.'</div>';
            unset($error_message);
        }
      ?>

        <form method="POST">


          <div>
            <label for="email" class="form-label"></label>
            <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
            <div class="invalid-feedback">Please enter a valid email.</div>
          </div>



          <br />
          <div>
            <div class="input-group">
              <input type="password" class="form-control" name="password" placeholder="Enter your password" required />
              <span class="password-toggle" onclick="togglePassword()">
                <i class="fas fa-eye" id="toggleIcon"></i>
              </span>
            </div>
          </div>


          <br />
          <button type="submit" class="btn btn-primary w-100 mx-auto"> Login </button>
          <a href="signup.php" class="text-decoration-none my-2">Don't have an account? Sign up!</a>


        </form>
      </div>
    </div>
  </body>
</html>
