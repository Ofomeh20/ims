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
        <script src="bootstrap.js"></script>
        <script src="bootstrap.min.js"></script>
</head>
<body class="p-5">
    <h1> WELCOME  TO BJ LIBRARY <?php echo $_SESSION['username'] ?></h1>
    <hr>
    <a href="borrowed.php">Books youve borrowed</a>

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
                    <button type="button" class="btn btn-secondary p-1" data-bs-toggle="modal" data-bs-target="#<?php echo $books['id']; ?>">
                        Borrow
                    </button>


                  <!-- Show a dropdown modal to input information when the user attempts to borrow -->
                    <div id="<?php echo $books['id'] ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form action="borrow.php" method="GET">
                                    <div class="modal-header">
                                        <button type="button" class="close btn" data-bs-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Borrow</h4>
                                    </div>
                                    <div class="modal-body">
                                        <label for="account_no">Account number:</label>
                                        <input type="number" name="account_no" class="form-control">
                                        <input type="hidden" name="id" value="<?php echo $books['id']; ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default" data-bs-dismiss="modal">
                                            Borrow
                                        </button>
                                    </div>
                                </form>
                            
                            </div>

                        </div>
                    </div>


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


            <button class="btn btn-secondary mt-4"><a href="logout.php" class="nav-link">Log out</a></button>
</body>
</html>