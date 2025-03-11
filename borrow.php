<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_Id'];

    //checks if the books are avaiable
    $stmt = $conn->prepare("SELECT avaiable FROM books WHERE id =?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(conn::FETCH_ASSOC);

    if ($book && $book['available']){
        //borrow a book
        $conn->prepare("INSERT INTO borrow(user_id , book_id)VALUES(?,?")->execute ([$user_id,$book_id]);
        $conn->prepare ("UPDATE books SET available = FALSE WHERE id = ?")->execute ([$book_id]);

        echo "BOOK borrowed succesfully!";
    }else{
        echo"Sorry, this book is not available.";
    }
}




?>




<form method="POST">
    User_id:<input type="number" name="user_id" required><br>
     Book_id:<input type="number" name="book_id"  value="<?php echo $book_id; ?>" required><br>
       <button type="submit"> Borrow book </button>
        </form>
