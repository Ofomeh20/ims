<?php
   session_start();
   include "db_connect.php";

   if (!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
  } elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == "admin") {
      header('Location: admin_dash.php');
      exit();
  }

   $stmt = $conn->prepare("SELECT * FROM borrowed_books WHERE borrower=?");
   $stmt->bind_param('s', $_SESSION['user_id']);
   $stmt->execute();

   $res =$stmt->get_result();
   $i = 0;

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title> library</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="index.css">
        <script src="bootstrap.js"></script>
        <script src="bootstrap.min.js"></script>
</head>
<body class="p-5">
    <h1> WELCOME  TO BJ LIBRARY <?php echo $_SESSION['username'] ?></h1>
    <hr>

    <?php if($res->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Author</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while($books = $res->fetch_assoc()): ?>
            <?php 
                $stmt = $conn->prepare("SELECT * FROM books WHERE title=? AND author=?");
                $stmt->bind_param('ss', $books['title'], $books['author']);
                $stmt->execute();
                $result = $stmt->get_result();
                $book = $result->fetch_assoc();
                $i++;
            ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $book['title']; ?></td>
                <td><?php echo $book['author']; ?></td>
                <td><?php echo $book['description']; ?></td>
                <td>
                    <button type="button" class="btn btn-secondary p-1">
                        <a href="return.php?user=<?php echo $books['borrower']. '&book='.$book['title'].'&author='.$book['author']; ?>" class="nav-link">Return</a>
                    </button>
                </td>
            </tr>

        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($res->num_rows == 0) {
                echo "<h3>You haven't borrowed any books</h3>";
            }
        ?>


            <button class="btn btn-secondary mt-4"><a href="logout.php" class="nav-link">Log out</a></button>
</body>
</html>