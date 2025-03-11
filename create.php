<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $author, $description);
    $stmt->execute();

    header("Location: index.php");
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
        <button type="submit">Publish</button>
    </form>
</body>
</html>
