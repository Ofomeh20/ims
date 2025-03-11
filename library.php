<?php
   session_start();
   include "db_connect.php";

   $stmt=$conn->prepare("SELECT * FROM users WHERE user_id=?");
   $stmt->bind_param('s',$_SESSION['user_id']);
   $stmt->execute();

   $result1 = $stmt->get_result();
   $row= $result1->fetch_assoc();

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
    <h1> WELCOEME  TO BJ LIBRAY </h1>
    <hr>
    <a href="create.php">Add New Book</a>

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
                <td>
                    <a href="#">Borrow</a>
                </td>
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