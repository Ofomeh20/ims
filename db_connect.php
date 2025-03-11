<?php
if(session_status() === PHP_SESSION_NONE) {
 session_start();
}

  $host = "localhost";
  $db_username = "root";
  $db_passkey = "";
  $dbname = "library";
  $conn = new mysqli($host, $db_username, $db_passkey, $dbname);

  if($conn->connect_error){
    die("Connection error:". $conn->connect_error);
  }

  // Creates the user table
  $users_query = "
  CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        user_id VARCHAR(255) NOT NULL
        )";
  $conn->query($users_query);

  //Creates table for profile
  $profile_table = "
  CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        author VARCHAR(255),
        description VARCHAR(255),
        borrowed VARCHAR(50),
        borrower  VARCHAR(255)
  )";
  $conn->query($profile_table);

  
?>
