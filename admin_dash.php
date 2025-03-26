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













    <!-- Shows all pending requests to borrow a book -->
    <?php if($result1->num_rows != 0): ?>
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

    <!-- loops through the result of the pending requests query toget the info on the who want to borrow books -->
        <?php while($req = $result1->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $req['username']; ?></td>
                <td><?php echo $req['email']; ?></td>
                <td><?php echo $req['book']; ?></td>
                <td><?php echo $req['author']; ?></td>
                <td><?php echo $req['price']; ?></td>
                <td>
                  <a href="accept.php?user=<?php echo $req['user_id'].'&book='. $req['book']; ?>" class="btn btn-secondary">
                    Approve
                  </a>
                  <a href="reject.php?user=<?php echo $req['user_id'].'&book='. $req['book']; ?>" class="btn btn-secondary">
                    Disapprove
                  </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result1->num_rows == 0) {
                echo "<h5>There are no pending borrow requests</h3>";
            }
        ?>













    <!-- Shows all pending requests to create an account by looping through the pending_accounts table -->
    <?php if($result->num_rows != 0): ?>
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
                echo "<h5>There are no pending account</h3>";
            }
        ?>





<h3 class="mt-4">List of members</h3>

        <!-- Shows all members -->
<?php if($res->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while($user = $res->fetch_assoc()): ?>
            <tr>
                <td><?php
                    $i++;
                    echo $i; 
                ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                  <a href="#" class="btn btn-secondary">
                    Suspend
                  </a>
                  <a href="#" class="btn btn-secondary">
                    Delete
                  </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($res->num_rows == 0) {
                echo "<h5>There are no members</h3>";
            }
        ?>








<h3 class="mt-4">List of books</h3>

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
                <td></td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result2->num_rows == 0) {
                echo "<h5>There are no available books</h3>";
            }
        ?>







<h3 class="mt-4">List of all borrowed books</h3>
<?php if($result3->num_rows != 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Book</th>
            <th>Author</th>
            <th>Time borrowed</th>
            <th>Date to be returned</th>
            <th>Actions</th>
        </tr>

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
                <td>
                    <?php 
                        $time->setTimestamp($book['time_borrowed']);
                        echo $time->format("d-m-Y H:i:s");
                    ?>
                 </td>
                 <td>
                    <?php 
                        $time->setTimestamp($book['due_date']);
                        echo $time->format("d-m-Y H:i:s");
                    ?>
                 </td>
                <td>
                  <button class="btn btn-secondary">
                    <a href="suspend.php?<?php echo $book['borrower']; ?>"></a>Suspend user
                </button>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
        <?php endif; 
            if ($result3->num_rows == 0) {
                echo "<h5>There are no borrowed books</h3>";
            }
        ?>
        


<button class="btn btn-secondary mt-4"><a href="logout.php" class="nav-link">Log out</a></button>

</body>
</html>