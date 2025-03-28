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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="bootstrap.js"></script>
        <script src="bootstrap.min.js"></script>
</head>
<body class="p-5">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">BJ Library</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log out</a>
                    </li>
                </ul>
            </div>
        </nav>

        <h5 class="text-center text-primary mt-1">WELCOME TO BJ LIBRARY, <?php echo $_SESSION['username']; ?></h5>


        <a href="borrowed.php">Books you've borrowed</a>
        <?php if($_SESSION['status'] != "suspended"): ?>

        <section class="mb-5">
            <h3 class="text-secondary">List of Books</h3>
            <?php if($result2->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Author</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($books = $result2->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $books['id']; ?></td>
                            <td><?php echo $books['title']; ?></td>
                            <td><?php echo $books['author']; ?></td>
                            <td><?php echo $books['description']; ?></td>
                            <td>
                                <?php if($books['borrowed'] != "true"): ?>
                                    <button type="button" class="btn btn-warning p-1" data-bs-toggle="modal" data-bs-target="#<?php echo $books['id']; ?>">
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
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no available books.</h5>
            <?php endif; ?>
        </section>
        <?php else: ?>
            <h1>Your account has been suspended</h1>
            <h5>It will not be reactivated until the book is returned and you npay a fine</h5>


            <button type="button" class="btn btn-success p-1" data-bs-toggle="modal" data-bs-target="#reactivate">
                Reactivate
            </button>


            <!-- Show a dropdown modal to input information when the user attempts to borrow -->
            <div id="reactivate" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form action="request_reactivation.php" method="GET">
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
                                    Send request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="text-center">
            <a href="logout.php" class="btn btn-danger">Log Out</a>
        </div>
</body>
</html>