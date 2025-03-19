<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, description, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $description, $price);
    $stmt->execute();

    header("Location: admin_dash.php");
    exit();
}
?>
<!--prodyct--->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Item</title>
</head>
<body>
    <h2>Create New Item</h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="title" required><br><br>

        <label>Author:</label>
        <input name="author" required><br><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br><br>

        <label>Price:</label>
        <input name="price" type="number" required><br><br>
        <button type="submit">Publish</button>

    </form>
</body>
</html>
