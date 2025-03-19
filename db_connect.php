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
        user_id VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL
        )";
  $conn->query($users_query);



  
    // Creates the user table
    $pending_users_query = "
    CREATE TABLE IF NOT EXISTS pending_accounts (
          id INT AUTO_INCREMENT PRIMARY KEY,
          username VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          password VARCHAR(255) NOT NULL,
          user_id VARCHAR(255) NOT NULL
          )";
    $conn->query($pending_users_query);



      // Creates the user table
  $pending_requests_query = "
  CREATE TABLE IF NOT EXISTS pending_requests (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      book VARCHAR(50) NOT NULL,
      author VARCHAR(50) NOT NULL,
      price INT NOT NULL,
      user_id VARCHAR(255) NOT NULL
)";
  $conn->query($pending_requests_query);



  //Creates table for profile
  $books_table = "
  CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        description VARCHAR(255),
        borrowed VARCHAR(50),
        price INT
  )";
  $conn->query($books_table);



  $borrowed_books_table = "
  CREATE TABLE IF NOT EXISTS borrowed_books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        author VARCHAR(255),
        borrower VARCHAR(255),
        time_borrowed VARCHAR(255),
        due_date VARCHAR(255),
        email_sent VARCHAR(50)
  )";
  $conn->query($borrowed_books_table);

  
?>
