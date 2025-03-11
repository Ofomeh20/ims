<?php 
session_start();
include 'db_connect.php';


 echo"<h1>Welcome to the library, ";
  if(isset($_SESSION['username'])){
echo $_SESSION['username'];
  }else{
    echo "Guest, please sign up ";
  }
   echo"!</h1>";
 
   $result = $conn->query("SELECT * FROM books");

 echo "<table border='1' cellpadding='10'>";
 echo "<tr><th>ID </th>
            <th> Title <th>ss
            <th> Author </th>
              <th> action </th>
         </tr>";

  while($book = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>{$book['id']}</td>";
    echo "<td>{$book['title']}</td>";
    echo "<td>{$book['author']}</td>";
    echo "<td><a href='library.php'>GO TO LIBRARY </a></td>";
    echo"<td> <a href='logout.php'><button>logout </button><a></td>";
    echo "</tr>";
  }   
  echo"</table>";
   
  ?>    