<?php
    session_start();
    include "db_connect.php";

    if (!isset($_SESSION['user_id'])){
      header('Location: login.php');
      exit();
    } elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == "member") {
        header('Location: index.php');
        exit();
    }
    

    $i = 0;

    $stmt=$conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $res = $stmt->get_result();

    $stmt=$conn->prepare("SELECT * FROM pending_accounts");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt=$conn->prepare("SELECT * FROM pending_requests");
    $stmt->execute();
    $result1 = $stmt->get_result();
    
    $stmt = $conn->prepare("SELECT * FROM books");
    $stmt->execute();
    $result2 =$stmt->get_result();

?>






<!DOCTYPE HTML>
<html>
    <head>
        <title> library</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body class="p-5">
    <h1> WELCOME TO BJ LIBRARY, <?php echo $_SESSION['username'] ?></h1>
    <hr>












<!-- Shows all pending requests to create an account by looping through the pending_accounts table -->

    <?php if($result->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Book</th>
            <th>Author</th>
            <th>price</th>
            <th>Actions</th>
        </tr>
        <?php while($book = $result1->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $book['username']; ?></td>
                <td><?php echo $book['email']; ?></td>
                <td><?php echo $book['book']; ?></td>
                <td><?php echo $book['author']; ?></td>
                <td><?php echo $book['price']; ?></td>
                <td>
                  <a href="accept.php?user=<?php echo $book['user_id']; ?>" class="btn btn-secondary">
                    Approve
                  </a>
                  <a href="reject.php?user=<?php echo $book['user_id']; ?>" class="btn btn-secondary">
                    Disapprove
                  </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result->num_rows == 0) {
                echo "<h3>There are no pending borrow requests</h3>";
            }
        ?>














    <!-- Shows all pending requests to borrow a book -->
    <?php if($result1->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                  <a href="approve.php?user=<?php echo $user['user_id']; ?>" class="btn btn-secondary">
                    Approve
                  </a>
                  <a href="disapprove.php?user=<?php echo $user['user_id']; ?>" class="btn btn-secondary">
                    Disapprove
                  </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result->num_rows == 0) {
                echo "<h3>There are no pending account</h3>";
            }
        ?>











<!-- For addind to the list of books, only accessible to admins -->
    <a href="create.php">Add New Book</a>

<!-- Creates a table of all the books by looping through the books table -->
    <?php if($result2->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Author</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while($books = $result2->fetch_assoc()): ?>
            <tr>
                <td><?php echo $books['id']; ?></td>
                <td><?php echo $books['title']; ?></td>
                <td><?php echo $books['author']; ?></td>
                <td><?php echo $books['description']; ?></td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result2->num_rows == 0) {
                echo "<h3>There are no available books</h3>";
            }
        ?>





</body>
</html>