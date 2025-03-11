<?php
include 'db_connect.php';

// Fetch items
$sql = "SELECT * FROM items";
$result = $conn->query($sql);//y loop 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD System</title>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this item?")) {
                window.location.href = 'delete.php?id=' + id;//=- to know if it sthe id we are working with
            }
        }
    </script>
</head>
<body>
    <h2>Item List</h2>
    <a href="create.php">Add New Book</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a><!---get post ---->
                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>


    create.php-> used for adding new books to the database
    library.php-> display books and provide a borrow buttton for users 
    borrow.php -> handles the logic when a user borrows a book

    then,
    in library.php->modiffy the borrow button to submit the book id to borrow.php 
    in borrow .php -> the logic to check if the book is available and add it to the users cart or borrowing list  
</body>
</html>
