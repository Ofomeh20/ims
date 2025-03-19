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
    <h1> WELCOME  TO BJ LIBRARY <?php echo $_SESSION['username'] ?></h1>
    <hr>

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

                  <?php if($books['borrowed'] != "true"): ?>
                    <a href="<?php echo "borrow.php?id={$books['id']}"; ?>">
                      Borrow
                    </a>
                  <?php endif;?>

                  <?php if($books['borrowed'] == "true"): ?>
                      Borrowed
                  <?php endif;?>


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