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

    // Shows all the registered users/members that have accounts on the site
    $stmt=$conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $res = $stmt->get_result();

    // Shows all the requests by users to create an account
    $stmt=$conn->prepare("SELECT * FROM pending_accounts");
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Shows all the requests by users to borrow a book
    $stmt=$conn->prepare("SELECT * FROM pending_requests");
    $stmt->execute();
    $result1 = $stmt->get_result();
    
    // Shows all the books registered on the site
    $stmt = $conn->prepare("SELECT * FROM books");
    $stmt->execute();
    $result2 =$stmt->get_result();

    // Shows all books that have been borrowed
    $stmt = $conn->prepare("SELECT * FROM borrowed_books");
    $stmt->execute();
    $result3 =$stmt->get_result();

    $stmt = $conn->prepare("SELECT * FROM pending_reactivation_requests");
    $stmt->execute();
    $result4 = $stmt->get_result();

?>






<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Library</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="index.css">
    </head>
    <body class="container py-5">

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

        <h6 class="text-center text-primary mt-1">WELCOME TO BJ LIBRARY, <?php echo $_SESSION['username']; ?></h6>

        <!-- Pending Borrow Requests -->
        <section class="mb-5">
            <h3 class="text-secondary">Pending Borrow Requests</h3>
            <?php if($result1->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th>Account no</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($req = $result1->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $req['username']; ?></td>
                            <td><?php echo $req['email']; ?></td>
                            <td><?php echo $req['book']; ?></td>
                            <td><?php echo $req['author']; ?></td>
                            <td><?php echo $req['price']; ?></td>
                            <td><?php echo $req['account_no']; ?></td>
                            <td>
                                <a href="accept.php?user=<?php echo $req['user_id'].'&book='. $req['book']; ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="reject.php?user=<?php echo $req['user_id'].'&book='. $req['book']; ?>" class="btn btn-danger btn-sm">Disapprove</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no pending borrow requests.</h5>
            <?php endif; ?>
        </section>





        <!-- Pending reactivation Requests -->
        <section class="mb-5">
            <h3 class="text-secondary">Pending reactivation Requests</h3>
            <?php if($result4->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Account no</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($req = $result4->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $req['username']; ?></td>
                            <td><?php echo $req['email']; ?></td>
                            <td><?php echo $req['account_no']; ?></td>
                            <td>
                                <a href="reactivate.php?user=<?php echo $req['user_id']; ?>" class="btn btn-success btn-sm">Reactivate User</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no pending reactivation requests.</h5>
            <?php endif; ?>
        </section>




        <!-- Pending Account Requests -->
        <section class="mb-5">
            <h3 class="text-secondary">Pending Account Requests</h3>
            <?php if($result->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <a href="approve.php?user=<?php echo $user['user_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="disapprove.php?user=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm">Disapprove</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no pending account requests.</h5>
            <?php endif; ?>
        </section>

        <!-- List of Members -->
        <section class="mb-5">
            <h3 class="text-secondary">List of Members</h3>
            <?php if($res->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <?php if ($user['status'] != "suspended"): ?>
                                <a href="suspend.php?user=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm">Suspend User</a>
                                <?php else: ?>
                                <a href="reactivate.php?user=<?php echo $user['user_id']; ?>" class="btn btn-success btn-sm">Reactivate User</a>
                                <?php endif ?>
                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no members.</h5>
            <?php endif; ?>
        </section>

        <!-- List of Books -->
        <section class="mb-5">
            <h3 class="text-secondary">List of Books</h3>
            <a href="create.php" class="btn btn-primary mb-3">Add New Book</a>
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
                                <a href="#" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
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

        <!-- Borrowed Books -->
        <section class="mb-5">
            <h3 class="text-secondary">List of Borrowed Books</h3>
            <?php if($result3->num_rows != 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Time Borrowed</th>
                            <th>Date to be Returned</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $result3->fetch_assoc()): ?>
                        <?php 
                            $time = new DateTime();
                            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
                            $stmt->bind_param('s', $book['borrower']);
                            $stmt->execute();
                            $r = $stmt->get_result();
                            $user = $r->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php 
                                $time->setTimestamp($book['time_borrowed']);
                                echo $time->format("d-m-Y H:i:s");
                            ?></td>
                            <td><?php 
                                $time->setTimestamp($book['due_date']);
                                echo $time->format("d-m-Y H:i:s");
                            ?></td>
                            
                            
                            <td>
                                <?php 
                                    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
                                    $stmt->bind_param("s", $book['borrower']);
                                    $stmt->execute();
                                    $r= $stmt->get_result();
                                    $susp = $r->fetch_assoc();
                                ?>

                                <?php if ($susp['status'] != "suspended"): ?>
                                <a href="suspend.php?user=<?php echo $book['borrower']; ?>" class="btn btn-warning btn-sm">Suspend User</a>
                                <?php else: ?>
                                <a href="reactivate.php?user=<?php echo $book['borrower']; ?>" class="btn btn-success btn-sm">Reactivate User</a>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h5 class="text-warning">There are no borrowed books.</h5>
            <?php endif; ?>
        </section>

        <!-- Log Out -->
        <div class="text-center">
            <a href="logout.php" class="btn btn-danger">Log Out</a>
        </div>
    </body>
</html>
